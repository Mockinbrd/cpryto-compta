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
        $user->setFirstname('Elon');
        $user->setLastname('Musk');
        $manager->persist($user);

        $user2 = new User();
        $user2->setEmail('user@crypto-compta.com');
        $user2->setPassword($this->passwordEncoder->encodePassword($user2, '123456'));
        $user2->setRoles(['ROLE_USER', 'ROLE_API_USER']);
        $user2->setIsVerified(true);
        $user2->setFirstname('James');
        $user2->setLastname('Franco');
        $manager->persist($user2);

        $user3 = new User();
        $user3->setEmail('non-verified@crypto-compta.com');
        $user3->setPassword($this->passwordEncoder->encodePassword($user3, '123456'));
        $user3->setRoles(['ROLE_USER']);
        $user3->setIsVerified(false);
        $user3->setFirstname('Wako');
        $user3->setLastname('Nakamoto');
        $manager->persist($user3);

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

        $transaction2 = new Transactions();
        $transaction2->setCoinId('ethereum');
        $transaction2->setCurrency('EUR');
        $transaction2->setAmount(200);
        $transaction2->setTokenQuantityReceived(0.2);
        $transaction2->setTransactionDate(new \DateTimeImmutable());
        $transaction2->setPortfolio($portfolio);
        $manager->persist($transaction2);

        $manager->flush();
    }
}
