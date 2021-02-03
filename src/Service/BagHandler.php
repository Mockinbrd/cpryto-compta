<?php

namespace App\Service;

use App\Client\CoinGeckoClient;
use App\Entity\Bag;
use App\Entity\Purchase;

class BagHandler {

    private CoinGeckoClient $coinGeckoClient;

    public function __construct(CoinGeckoClient $coinGeckoClient)
    {
        $this->coinGeckoClient = $coinGeckoClient;
    }

    private function getCoinIcon(string $id): string
    {
        $infos = $this->coinGeckoClient->info($id);
        return $infos['image']['small'];
    }

    public function createBagFromPurchase(Purchase $purchase): Bag
    {
        $user = $purchase->getUser();
        $icon = $this->getCoinIcon($purchase->getCoinName());

        $bag = new Bag();
        $bag->setIcon($icon);
        $bag->setName($purchase->getCoinName());
        $bag->setUser($user);
        $bag->addPurchase($purchase);
        return $bag;
    }

}