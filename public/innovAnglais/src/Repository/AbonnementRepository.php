<?php

namespace App\Repository;

use App\Entity\Abonnement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Abonnement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Abonnement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Abonnement[]    findAll()
 * @method Abonnement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbonnementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Abonnement::class);
    }

    // /**
    //  * @return Abonnement[] Returns an array of Abonnement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Abonnement
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    
    
    public function selectAllSubscriptions(): array {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT id AS idSubscription, libelle  AS libelleSubscription, nb_fois AS nb_foisSubscription '
                . 'FROM abonnement a ';

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }  
    
    public function insertSubscription($libelle, $nb_fois) {
        $conn = $this->getEntityManager()->getConnection();
        
        $result = array();
        $sql_insert_subscription = "INSERT INTO abonnement (`libelle`, `nb_fois`) 
                                    VALUES (:libelle, :nb_fois)";
        $req_insert_subscription = $conn->prepare($sql_insert_subscription);
        $req_insert_subscription->bindValue(':libelle', $libelle);
        $req_insert_subscription->bindValue(':nb_fois', $nb_fois);
        if($req_insert_subscription->execute()) {
            $result['result'] = "success";
        }
        return $result;
    }
}
