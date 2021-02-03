<?php

namespace App\Repository;

use App\Entity\Bag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Bag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bag[]    findAll()
 * @method Bag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bag::class);
    }

    // /**
    //  * @return Crypto[] Returns an array of Crypto objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Crypto
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
