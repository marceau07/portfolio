<?php

namespace App\Repository;

use App\Entity\Entreprise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Entreprise|null find($id, $lockMode = null, $lockVersion = null)
 * @method Entreprise|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entreprise[]    findAll()
 * @method Entreprise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntrepriseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entreprise::class);
    }

    // /**
    //  * @return Entreprise[] Returns an array of Entreprise objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Entreprise
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    
        public function selectAllFirms(): array {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT id AS idFirm, nom  AS nameFirm '
                . 'FROM entreprise e ';

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
    
    public function insertFirm($nom) {
        $conn = $this->getEntityManager()->getConnection();
        
        $result = array();
        $sql_insert_firm = "INSERT INTO entreprise (`nom`) 
                            VALUES (:nom)";
        $req_insert_firm = $conn->prepare($sql_insert_firm);
        $req_insert_firm->bindValue(':nom', $nom);
        if($req_insert_firm->execute()) {
            $result['result'] = "success";
        }
        return $result;
    }
}
