<?php

namespace App\Repository;

use App\Entity\NiveauUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NiveauUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method NiveauUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method NiveauUser[]    findAll()
 * @method NiveauUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NiveauUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NiveauUser::class);
    }

    // /**
    //  * @return NiveauUser[] Returns an array of NiveauUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NiveauUser
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
