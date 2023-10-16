<?php

namespace App\Repository;

use App\Entity\Payer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Payer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Payer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Payer[]    findAll()
 * @method Payer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Payer::class);
    }

    // /**
    //  * @return Payer[] Returns an array of Payer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Payer
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
