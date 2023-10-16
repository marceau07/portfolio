<?php

namespace App\Repository;

use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Categorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categorie[]    findAll()
 * @method Categorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorie::class);
    }

    // /**
    //  * @return Categorie[] Returns an array of Categorie objects
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
    public function findOneBySomeField($value): ?Categorie
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
        public function selectAllCategorys(): array {
        $conn = $this->getEntityManager()->getConnection();
         
         $sql = 'SELECT id AS idCategorie, libelle  AS libelleCategorie '
                 . 'FROM categorie c ';
         
         $stmt = $conn->prepare($sql);
         $stmt->execute();
         
         return $stmt->fetchAll();
    }
    
    public function insertCategorie($libelle) {
        $conn = $this->getEntityManager()->getConnection();
        
        $result = array();
        $sql_insert_category = "INSERT INTO categorie (`libelle`) 
                                VALUES (:libelle)";
        $req_insert_category = $conn->prepare($sql_insert_category);
        $req_insert_category->bindValue(':libelle', $libelle);
        if($req_insert_category->execute()) {
            $result['result'] = "success";
        }
        return $result;
    }
}
