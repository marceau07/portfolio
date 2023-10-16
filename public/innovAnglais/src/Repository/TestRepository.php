<?php

namespace App\Repository;

use App\Entity\Test;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Test|null find($id, $lockMode = null, $lockVersion = null)
 * @method Test|null findOneBy(array $criteria, array $orderBy = null)
 * @method Test[]    findAll()
 * @method Test[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Test::class);
    }

    // /**
    //  * @return Test[] Returns an array of Test objects
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
      public function findOneBySomeField($value): ?Test
      {
      return $this->createQueryBuilder('t')
      ->andWhere('t.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */

    public function selectAllTests(): array {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT t.id AS test_id, n.id AS level_id, th.libelle AS theme_label, n.libelle AS level_label, t.libelle AS test_label '
                . 'FROM test t '
                . 'INNER JOIN niveau n '
                . 'ON n.id=t.niveau_id '
                . 'INNER JOIN theme th '
                . 'ON th.id=t.theme_id ';

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function selectLeTest($id): array {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT t.id AS idTest,t.libelle AS libelleTest, n.id AS idNiveau, n.libelle AS libelleNiveau, th.id AS `idTheme`, th.libelle AS libelleTheme, question_1_label, question_1_fake_words, question_1_answer, question_2_label, question_2_fake_words, question_2_answer, question_3_label, question_3_fake_words, question_3_answer, question_4_label, question_4_fake_words, question_4_answer
                FROM test t 
                INNER JOIN theme th ON th.id=t.theme_id
                INNER JOIN niveau n ON n.id=t.niveau_id
                Where t.id=' . $id;

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function selectAllTheme(): array {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT *
                FROM theme t';

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function selectTestByTheme($id): array {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT test.id AS `id`, niveau_id, test.libelle AS `test`, niveau.libelle AS `niveau`, test.icone AS `icone`
                FROM `test`
                LEFT JOIN niveau
                ON niveau.id = niveau_id
                LEFT JOIN theme
                ON theme.id = theme_id
                WHERE theme_id = '. $id .'
                ORDER BY `niveau_id` ASC';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function selectThemeName($id): array {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT theme.libelle
                FROM `theme`
                WHERE theme.id = '. $id;

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function selectAllLevels(): array {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT niveau.id AS `idNiveau`, niveau.libelle
                FROM `niveau`';

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

}
