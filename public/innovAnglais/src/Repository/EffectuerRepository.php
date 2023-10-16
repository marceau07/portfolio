<?php

namespace App\Repository;

use App\Entity\Effectuer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Effectuer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Effectuer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Effectuer[]    findAll()
 * @method Effectuer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EffectuerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Effectuer::class);
    }
    
    public function insertEffectuer($test_id, $user_id, $date) : array {
        $conn = $this->getEntityManager()->getConnection();
        
        $result = array();
        $sql_select_test = "SELECT e.* "
                         . "FROM effectuer e "
                         . "WHERE e.test_id=:test_id AND e.user_id=:user_id AND e.date=:date ";
        $req_select_test = $conn->prepare($sql_select_test);
        $req_select_test->bindParam(':test_id', $test_id);
        $req_select_test->bindParam(':user_id', $user_id);
        $req_select_test->bindParam(':date', $date);
        $req_select_test->execute();
        $effectuer = $req_select_test->fetch();
        if(empty($effectuer)) {
            $sql_inserer_test = "INSERT INTO effectuer(`test_id`, `date`, `user_id`)"
                              . "VALUES(:test_id, :date, :user_id)";
            $req_inserer_test = $conn->prepare($sql_inserer_test);
            $req_inserer_test->bindParam(':test_id', $test_id);
            $req_inserer_test->bindParam(':date', $date);
            $req_inserer_test->bindParam(':user_id', $user_id);
            if($req_inserer_test->execute()) {
                $result['success'] = true;
                $result['resultat'] = $effectuer['resultat'];
                $result['message'] = "Démarrage du chrono !";
            } else {
                $result['success'] = false;
                $result['resultat'] = $effectuer['resultat'];
                $result['message'] = "Une erreur est survenue";
            }
        } else {
            $result['success'] = false;
            $result['resultat'] = $effectuer['resultat'];
            $result['message'] = "Vous avez déjà effectué ce test";
        }
        return $result;
    }
    
    public function updateEffectuer($test_id, $user_id, $date, $resultat, $temps) {
        $conn = $this->getEntityManager()->getConnection();
        
        $result = array();
        $sql_update_effectuer = "UPDATE effectuer "
                . "SET resultat=:resultat, temps=:temps "
                . "WHERE test_id=:test_id AND date=:date AND user_id=:user_id ";
        $req_update_effectuer = $conn->prepare($sql_update_effectuer);
        $req_update_effectuer->bindValue(':resultat', $resultat);
        $req_update_effectuer->bindValue(':temps', $temps);
        $req_update_effectuer->bindParam(':test_id', $test_id);
        $req_update_effectuer->bindParam(':date', $date);
        $req_update_effectuer->bindParam(':user_id', $user_id);
        if($req_update_effectuer->execute()) {
            $result['success'] = true;
        }
        return $result;
    }
    
    public function selectEffectuer($test_id, $user_id, $date) {
        $conn = $this->getEntityManager()->getConnection();
        
        $result = array();
        $sql_select_effectuer = "SELECT e.* "
                . "FROM effectuer e "
                . "WHERE test_id=:test_id AND date=:date AND user_id=:user_id ";
        $req_select_effectuer = $conn->prepare($sql_select_effectuer);
        $req_select_effectuer->bindParam(':test_id', $test_id);
        $req_select_effectuer->bindParam(':date', $date);
        $req_select_effectuer->bindParam(':user_id', $user_id);
        $req_select_effectuer->execute();
        return $req_select_effectuer->fetch();
    }

    // /**
    //  * @return Effectuer[] Returns an array of Effectuer objects
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
    public function findOneBySomeField($value): ?Effectuer
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
