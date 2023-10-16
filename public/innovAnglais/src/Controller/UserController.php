<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserController extends AbstractController {

    /**
     * @Route("/user_add", name="user_add")
     */
    public function add(Request $request) {
        $user = new User();
        $form = $this->createFormBuilder($user)
                ->add('nom', TextType::class)
                ->add('prenom', TextType::class)
                ->add('age', TextType::class)
                ->add('niveau', TextType::class)
                ->add('entreprise', TextType::class)
                ->add('email', TextType::class)
                ->add('mdp', TextType::class)
                ->add('role_id', TextType::class, array('label' => 'Ajouter'))
                ->add('email', TextType::class)
                ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
            }
        }
        return $this->render('user/add.html.twig', array('form' => $form->createView(),
        ));
    }

    /**
     * @Route("/user_list", name="user_list")
     */ public function liste() {
        $repository = $this->getDoctrine()->getManager()->getRepository(User::class);
        $listUser = $repository->findAll();
        return $this->render('user/user_list.html.twig', [
                    'listUser' => $listUser,
        ]);
    }

    /**
     * @Route("/user_insert_android/{username}/{password}", name="user_insert_android")
     */ 
    public function userInsertAndroid(Request $request, UserPasswordEncoderInterface $passwordEncoder, UserRepository $repository) {
        $form = array();
        $username = $request->get('username');
        $password = $request->get('password');
        if($username != null && $password != null) {
            $newUser = new User();
            $newUser->setUsername($username);
            $newUser->setPassword($password);
            $newUser->setPassword($passwordEncoder->encodePassword($newUser, $newUser->getPassword()));
            $form['success'] = $repository->insertUser($newUser);
        }
        return $this->json($form);
    }

    /**
     * @Route("/users_JSON", name="users_JSON")
     */
    public function usersJSON(UserRepository $repository) {
        $users = $repository->selectAllUsers();
        for ($i = 0; $i < sizeof($users); $i++) {
            if ($users[$i]['roleUser'] == "[\"ROLE_USER\"]") {

                $users[$i]['roleUser'] = 'Utilisateur';
            } else {
                $users[$i]['roleUser'] = 'Admin';
            }
            if ($users[$i]['libellePaiement'] == null) {

                $users[$i]['libellePaiement'] = 'Pas de paiement';
            }
        }

        return $this->json($users);
    }

    /**
     * @Route("/logInAndroid/{username}", name="logInAndroid")
     */
    public function logInAndroid(Request $request, UserRepository $repository) {
        $username = $request->get('username');
        if ($username != null) {
            $user = $repository->selectAnUser($username);
            if ($user != null) {
                if ($user['roles'] == "[\"ROLE_USER\"]") {
                    $user['roles'] = 'Utilisateur';
                } else {
                    $user['roles'] = 'Administrateur';
                }
                return $this->json($user);
            }
        }
        return $this->json($user);
    }

    /**
     * @Route("/forgetPasswordAndroid/{username}", name="forgetPasswordAndroid")
     */
    public function forgetPasswordAndroid(Request $request, UserRepository $repository) {
        $username = $request->get('username');
        if ($username != null) {
            $user = $repository->selectAnUser($username);
            if ($user != null) {
                $user = $repository->forgetPassword($user['email']);
            }
        }
        return $this->json($user);
    }

    /**
     * @Route("/account_JSON/{username}", name="account_JSON")
     */
    public function accountJSON(Request $request, UserRepository $repository) {
        $username = $request->get('username');
        if ($username != null) {
            $user = $repository->selectAnUser($username);
            if ($user != null) {
                if ($user['roles'] == "[\"ROLE_USER\"]") {
                    $user['roles'] = 'Utilisateur';
                } else {
                    $user['roles'] = 'Administrateur';
                }
                return $this->json($user);
            }
        }
        return $this->json($user);
    }
    
    /**
     * @Route("/account_update_android/{valeur}/{user_id}", name="account_update_android")
     */
    public function accountUpdateAndroid(Request $request, UserRepository $repository) {
        $form = array();
        $valeur = $request->get('valeur');
        $user_id = $request->get('user_id');
        if ($user_id != null && $valeur != null) {  
            $form['success'] = $repository->updateUser($valeur, $user_id);
        }
        return $this->json($form);
    }
    
    /**
     * @Route("/account_update_parameters_android/{valeur}/{user_id}", name="account_update_parameters_android")
     */
    public function accountUpdateParametersAndroid(Request $request, UserRepository $repository) {
        $form = array();
        $valeur = $request->get('valeur');
        $user_id = $request->get('user_id');
        if ($user_id != null && $valeur != null) {  
            $form['success'] = $repository->updateParametersUser($valeur, $user_id);
        }
        return $this->json($form);
    }
    
    /**
     * @Route("/parameters_JSON", name="parameters_JSON")
     */
    public function parametersJSON(UserRepository $repository) {
        $parameters = $repository->selectParameters();

        return $this->json($parameters);
    }
}
