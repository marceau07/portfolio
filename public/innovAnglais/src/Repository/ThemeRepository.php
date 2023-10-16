<?php

namespace App\Repository;

use App\Entity\Theme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Theme|null find($id, $lockMode = null, $lockVersion = null)
 * @method Theme|null findOneBy(array $criteria, array $orderBy = null)
 * @method Theme[]    findAll()
 * @method Theme[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThemeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Theme::class);
    }

    // /**
    //  * @return Theme[] Returns an array of Theme objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Theme
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function selectAllThemes(): array {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT id AS idTheme, libelle  AS libelleTheme '
                . 'FROM theme t ';

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function insertTheme($libelle) {
        $conn = $this->getEntityManager()->getConnection();
        
        $result = array();
        $sql_insert_theme = "INSERT INTO theme (`libelle`) 
                                VALUES (:libelle)";
        $req_insert_theme= $conn->prepare($sql_insert_theme);
        $req_insert_theme->bindValue(':libelle', $libelle);
        if($req_insert_theme->execute()) {
            $result['result'] = "success";
        }
        return $result;
    }
}
