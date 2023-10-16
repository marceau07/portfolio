<?php

namespace App\Repository;

use App\Entity\Niveau;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Niveau|null find($id, $lockMode = null, $lockVersion = null)
 * @method Niveau|null findOneBy(array $criteria, array $orderBy = null)
 * @method Niveau[]    findAll()
 * @method Niveau[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NiveauRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Niveau::class);
    }

    // /**
    //  * @return Niveau[] Returns an array of Niveau objects
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
      public function findOneBySomeField($value): ?Niveau
      {
      return $this->createQueryBuilder('n')
      ->andWhere('n.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */
    public function selectAllLevels(): array {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT id AS idLevel, libelle  AS libelleLevel '
                . 'FROM niveau n ';

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
 
    public function insertLevel($libelle) {
        $conn = $this->getEntityManager()->getConnection();
        
        $result = array();
        $sql_insert_level = "INSERT INTO niveau (`libelle`) 
                                VALUES (:libelle)";
        $req_insert_level = $conn->prepare($sql_insert_level);
        $req_insert_level->bindValue(':libelle', $libelle);
        if($req_insert_level->execute()) {
            $result['result'] = "success";
        }
        return $result;
    }
}
