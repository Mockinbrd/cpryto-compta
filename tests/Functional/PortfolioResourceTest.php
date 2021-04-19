<?php

namespace App\Tests\Functional;

use App\Entity\Portfolio;
use App\Entity\User;
use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class PortfolioResourceTest extends CustomApiTestCase
{
    use ReloadDatabaseTrait;

    public function testCreatePortfolio()
    {
        $client = self::createClient();
        $client->request('POST', '/api/cheeses', [
            'json' => [],
        ]);
        $this->assertResponseStatusCodeSame(401);

        $authenticatedUser = $this->createUserAndLogIn($client, 'cheeseplease@example.com', 'foo');
        $otherUser = $this->createUser('otheruser@example.com', 'foo');

        $cheesyData = [
            'title' => 'Mystery cheese... kinda green',
            'description' => 'What mysteries does it hold?',
            'price' => 5000
        ];

        $client->request('POST', '/api/cheeses', [
            'json' => $cheesyData,
        ]);
        $this->assertResponseStatusCodeSame(201);

        $client->request('POST', '/api/cheeses', [
            'json' => $cheesyData + ['owner' => '/api/users/'.$otherUser->getId()],
        ]);
        $this->assertResponseStatusCodeSame(400, 'not passing the correct owner');

        $client->request('POST', '/api/cheeses', [
            'json' => $cheesyData + ['owner' => '/api/users/'.$authenticatedUser->getId()],
        ]);
        $this->assertResponseStatusCodeSame(201);
    }

    public function testUpdatePortfolio()
    {
        $client = self::createClient();
        $user1 = $this->createUser('user1@example.com', 'foo');
        $user2 = $this->createUser('user2@example.com', 'foo');

        $Portfolio = new Portfolio('Block of cheddar');
        $Portfolio->setOwner($user1);
        $Portfolio->setPrice(1000);
        $Portfolio->setDescription('mmmm');
        $Portfolio->setIsPublished(true);

        $em = $this->getEntityManager();
        $em->persist($Portfolio);
        $em->flush();

        $this->logIn($client, 'user2@example.com', 'foo');
        $client->request('PUT', '/api/cheeses/'.$Portfolio->getId(), [
            // try to trick security by reassigning to this user
            'json' => ['title' => 'updated', 'owner' => '/api/users/'.$user2->getId()]
        ]);
        $this->assertResponseStatusCodeSame(403, 'only author can updated');

        $this->logIn($client, 'user1@example.com', 'foo');
        $client->request('PUT', '/api/cheeses/'.$Portfolio->getId(), [
            'json' => ['title' => 'updated']
        ]);
        $this->assertResponseStatusCodeSame(200);
    }

    public function testGetPortfolioCollection()
    {
        $client = self::createClient();
        $user = $this->createUser('cheeseplese@example.com', 'foo');

        $Portfolio1 = new Portfolio('cheese1');
        $Portfolio1->setOwner($user);
        $Portfolio1->setPrice(1000);
        $Portfolio1->setDescription('cheese');

        $Portfolio2 = new Portfolio('cheese2');
        $Portfolio2->setOwner($user);
        $Portfolio2->setPrice(1000);
        $Portfolio2->setDescription('cheese');
        $Portfolio2->setIsPublished(true);

        $Portfolio3 = new Portfolio('cheese3');
        $Portfolio3->setOwner($user);
        $Portfolio3->setPrice(1000);
        $Portfolio3->setDescription('cheese');
        $Portfolio3->setIsPublished(true);

        $em = $this->getEntityManager();
        $em->persist($Portfolio1);
        $em->persist($Portfolio2);
        $em->persist($Portfolio3);
        $em->flush();

        $client->request('GET', '/api/cheeses');
        $this->assertJsonContains(['hydra:totalItems' => 2]);
    }

    public function testGetPortfolioItem()
    {
        $client = self::createClient();
        $user = $this->createUserAndLogIn($client, 'cheeseplese@example.com', 'foo');

        $Portfolio1 = new Portfolio('cheese1');
        $Portfolio1->setOwner($user);
        $Portfolio1->setPrice(1000);
        $Portfolio1->setDescription('cheese');
        $Portfolio1->setIsPublished(false);

        $em = $this->getEntityManager();
        $em->persist($Portfolio1);
        $em->flush();

        $client->request('GET', '/api/cheeses/'.$Portfolio1->getId());
        $this->assertResponseStatusCodeSame(404);

        $client->request('GET', '/api/users/'.$user->getId());
        $data = $client->getResponse()->toArray();
        $this->assertEmpty($data['Portfolios']);
    }
}
