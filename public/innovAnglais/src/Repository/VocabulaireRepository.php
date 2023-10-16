<?php

namespace App\Repository;

use App\Entity\Vocabulaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Vocabulaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vocabulaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vocabulaire[]    findAll()
 * @method Vocabulaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VocabulaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vocabulaire::class);
    }

    // /**
    //  * @return Vocabulaire[] Returns an array of Vocabulaire objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Vocabulaire
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    
        public function selectAllVocabularys(): array {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT id AS idVocabulary, libelle_fr  AS libelleVocabulary '
                . 'FROM vocabulaire v ';

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
