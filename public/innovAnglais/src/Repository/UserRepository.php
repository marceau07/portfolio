<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
      public function findByExampleField($value)
      {
      return $this->createQueryBuilder('u')
      ->andWhere('u.exampleField = :val')
      ->setParameter('val', $value)
      ->orderBy('u.id', 'ASC')
      ->setMaxResults(10)
      ->getQuery()
      ->getResult()
      ;
      }
     */

    /*
      public function findOneBySomeField($value): ?User
      {
      return $this->createQueryBuilder('u')
      ->andWhere('u.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */

    public function selectAnUser(String $username) {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT u.id, e.id AS firm_id, e.nom AS firm, u.username, u.roles, u.last_name_user AS lastName, u.first_name_user AS firstName, u.age, email, nu.libelle_level_user AS `level`, a.libelle AS libellePaiement, pa.value_parameter AS `langage`
                FROM user u 
                LEFT JOIN niveau_user nu ON nu.id = u.niveau_id
                LEFT JOIN payer p ON p.utilisateur_id = u.id
                LEFT JOIN abonnement a ON a.id = p.abonnement_id
                LEFT JOIN entreprise e ON e.id=u.entreprise_id
                LEFT JOIN parameters pa ON pa.id_user = u.id
                WHERE u.username=\'' . $username . '\'';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function forgetPassword(String $email): array {
        $v = array();
        if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $v['message'] = "Un mail vous a été envoyé.";
            $v['isOk'] = true;

            //Envoie d'un mail de confirmation//
            $header = "MIME-Version: 1.0\r\n";
            $header .= 'From:"innovAnglais.fr"<no-reply@innovanglais.fr>' . "\n";
            $header .= 'Content-Type:text/html; charset="uft-8"' . "\n";
            $header .= 'Content-Transfer-Encoding: 8bit';

            $message = "
                    <html>
                        <body>
                            <div align='center'>
                                Salut $email !<br/>
                                <p>Tu as oublié ton mot de passe ? Pas de problème, colle le mot de passe ci-dessous pour te connecter sur l'application</p>
                            </div>
                        </body>
                    </html>
                   ";

            mail($email, "Innov'Anglais - Mot de passe oublié", $message, $header);
        //fin d'envoi du mail//
        } else {
            $v['message'] = "Une erreur s'est produite.";
            $v['isOk'] = false;
        }
        return ($v);
    }

    public function selectAllUsers(): array {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT u.id AS idUser, username AS usernameUser, roles AS roleUser,
            last_name_user, first_name_user, e.nom AS firmUser, age AS ageUser,
            nu.libelle_level_user AS levelUser, a.libelle AS libellePaiement, email, IF(pa.value_parameter IS NULL, "fr", pa.value_parameter) AS language
               FROM user u
               INNER JOIN niveau_user nu ON nu.id=u.niveau_id
               INNER JOIN entreprise e ON e.id=u.entreprise_id
               LEFT JOIN payer p ON u.id=p.utilisateur_id
               LEFT JOIN abonnement a ON a.id=p.abonnement_id
               LEFT JOIN parameters pa ON pa.id_user=u.id';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getEmailLoginFailed(String $username) {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT email
                FROM user
                WHERE username =\'' . $username . '\'';

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetch();
    }
    
    public function insertUser(User $newUser) {
        $conn = $this->getEntityManager()->getConnection();
        
        // Initialisation des variables
        $random = random_int(0, 10000);
        $role = '["ROLE_USER"]';
        $result = array();
        $sql_insert_user = 'INSERT INTO `user`(`entreprise_id`, `niveau_id`, `username`, `roles`, `password`, `last_name_user`, `first_name_user`, `age`, `email`, `n_mdp`, `reset_token`) 
                            VALUES (NULL, 0, :username, :role, :password, "user_' . $random . '", "user_' . $random . '", 0, "utilisateur_' . $random . '@innov-anglais.fr", NULL, NULL)
                            ';
        $req_insert_user= $conn->prepare($sql_insert_user);
        $req_insert_user->bindValue(':username', $newUser->getUsername());
        $req_insert_user->bindValue(':role', $role);
        $req_insert_user->bindValue(':password', $newUser->getPassword());
        if($req_insert_user->execute()) {
//            $idUser = $conn->lastInsertId();
//            $sql_select_parameters = 'SELECT p.id_users
//                                      FROM parameters p';
//            $req_select_parameters = $conn->prepare($sql_select_parameters);
//            $parameters = $req_select_parameters->fetchAll();
//            $langueFr = (!empty($parameters[0]) ? array($parameters[0], $idUser) : array($idUser));
//            $langueEs = (!empty($parameters[1]) ? array($parameters[1], $idUser) : array($idUser));
//            $langueDe = (!empty($parameters[2]) ? array($parameters[2], $idUser) : array($idUser));
//            $langueAr = (!empty($parameters[3]) ? array($parameters[3], $idUser) : array($idUser));
//            
//            $conn->query('UPDATE `parameters` SET `id_users`=' . $langueFr . ' WHERE value_parameter = "fr"');
//            $conn->query('UPDATE `parameters` SET `id_users`=' . $langueEs . ' WHERE value_parameter = "es"');
//            $conn->query('UPDATE `parameters` SET `id_users`=' . $langueDe . ' WHERE value_parameter = "de"');
//            $conn->query('UPDATE `parameters` SET `id_users`=' . $langueAr . ' WHERE value_parameter = "ar"');
            $result['result'] = "success";
        }
        return $result;
    }
    
    public function updateUser($valeur, $user_id) {
        $conn = $this->getEntityManager()->getConnection();
        
        $result = array();
        $sql_update_effectuer = "UPDATE user u "
                . "SET entreprise_id=(SELECT e.id FROM entreprise e WHERE e.nom=:valeur) "
                . "WHERE u.id=:user_id ";
        $req_update_effectuer = $conn->prepare($sql_update_effectuer);
        $req_update_effectuer->bindValue(':valeur', $valeur);
        $req_update_effectuer->bindParam(':user_id', $user_id);
        if($req_update_effectuer->execute()) {
            $result['success'] = true;
        }
        return $result;
    }
    
    public function updateParametersUser($valeur, $user_id) {
        $conn = $this->getEntityManager()->getConnection();
        
        $result = array();
        $sql_update_trigger = "CALL resetParameters; ";
        $req_trigger_parameters = $conn->prepare($sql_update_trigger);
        $req_trigger_parameters->execute();
                
        $sql_update_parameters = "UPDATE parameters p "
                . "SET p.id_user=:user_id "
                . "WHERE value_parameter=:valeur ";
        $req_update_parameters = $conn->prepare($sql_update_parameters);
        $req_update_parameters->bindValue(':user_id', $user_id);
        $req_update_parameters->bindParam(':valeur', $valeur);
        if($req_update_parameters->execute()) {
            $result['success'] = true;
        }
        return $result;
    }
    
    public function selectParameters() : array {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT p.* 
                FROM parameters p ';

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
