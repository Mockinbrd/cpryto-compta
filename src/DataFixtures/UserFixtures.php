<?php

namespace App\DataFixtures;

use App\Entity\Portfolio;
use App\Entity\Transactions;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('admin@crypto-compta.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, '123456!?'));
        $user->setRoles(['ROLE_ADMIN']);
        $user->setIsVerified(true);
        $manager->persist($user);

        $portfolio = new Portfolio();
        $portfolio->setName('Main Portfolio');
        $portfolio->setUser($user);
        $manager->persist($portfolio);

        $transaction = new Transactions();
        $transaction->setCoinId('bitcoin');
        $transaction->setCurrency('EUR');
        $transaction->setAmount(100);
        $transaction->setTokenQuantityReceived(0.005);
        $transaction->setTransactionDate(new \DateTimeImmutable());
        $transaction->setPortfolio($portfolio);
        $manager->persist($transaction);

        $manager->flush();
    }
}
