<?php

namespace App\Service;

use App\Client\CoinGeckoClient;
use App\Entity\Bag;
use App\Entity\Purchase;
use App\Entity\User;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Exception\DriverException;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception\RuntimeException;
use Symfony\Component\Security\Core\User\UserInterface;

class PurchaseHandler {

    private CoinGeckoClient $coinGeckoClient;
    private EntityManagerInterface $em;
    private BagHandler $bagHandler;

    public function __construct(CoinGeckoClient $coinGeckoClient, EntityManagerInterface $em, BagHandler $bagHandler)
    {
        $this->coinGeckoClient = $coinGeckoClient;
        $this->em = $em;
        $this->bagHandler = $bagHandler;
    }

    private function createPurchase(array $data, UserInterface $user): Purchase
    {
        try {
            $date = new \DateTimeImmutable($data['purchaseDateType']);
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }
        $purchase = new Purchase();
        $purchase->setCoinName($data['coinId']);
        $purchase->setPurchaseDate($date);
        $purchase->setAmountToCrypto($data['amountToCrypto']);
        $purchase->setUser($user);
        return $purchase;
    }

    private function createBag(Purchase $purchase): ?Bag
    {
        $user = $purchase->getUser();
        $bag = $this->checkBagExistence($purchase);
        if ($bag) {
            $bag->addPurchase($purchase);
        } else {
            return $this->bagHandler->createBagFromPurchase($purchase);
        }
        return null;
    }

    private function checkBagExistence(Purchase $purchase): ?Bag
    {
        $user = $purchase->getUser();
        /* @var User $user */
        $bags = $user->getBags();
        if ($bags){
            foreach ($bags as $bag){
                if ($bag->getName() === $purchase->getCoinName()){
                    return $bag;
                }
            }
        }
        return null;
    }

    public function findEquivalentToEUR(string $id, float $amount, \DateTimeImmutable $date): float
    {
        $history = $this->coinGeckoClient->history($id,$date);
        $valueToEUR = $history['market_data']['current_price']['eur'];
        return round(($valueToEUR * $amount), 2);
    }

    public function handle(array $data, UserInterface $user): void
    {
        $purchase = $this->createPurchase($data, $user);

        /* @var Purchase $purchase*/
        $amountToEUR = $this->findEquivalentToEUR($purchase->getCoinName(),$purchase->getAmountToCrypto(), $purchase->getPurchaseDate());
        $purchase->setAmountToEUR($amountToEUR);
        /* Create the bag if not exists or add the purchase to it*/
        $bag = $this->createBag($purchase);

        try {
            if ($bag){
                $this->em->persist($bag);
            }
            $this->em->persist($purchase);
            $this->em->flush();
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }
}