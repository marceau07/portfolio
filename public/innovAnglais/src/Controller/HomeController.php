<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Fichier;

class HomeController extends AbstractController {

    /**
     * @Route("/", name="accueil")
     */ public function index(Request $request) {
            return $this->render('home/index.html.twig', [
                        'controller_name' => 'AccueilController',
            ]);
    }

    /**
     * @Route("/faq", name="faq")
     */
    public function faq() {
        return $this->render('home/faq.html.twig', [
                    'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function about() {
        return $this->render('home/about.html.twig', [
                    'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/legalnotices", name="legalnotices")
     */
    public function legalnotices() {
        return $this->render('home/legalnotices.html.twig', [
                    'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/signin", name="signin")    
     */
    public function signin(Request $request, UserPasswordEncoderInterface $passwordEncoder) {
        $user = new User();
        $form = $this->createFormBuilder($user)
                        ->add('username', TextType::class, array('attr' => array('placeholder' => 'johndoe')))
                        ->add('lastNameUser', TextType::class, array('attr' => array('placeholder' => 'Doe')))
                        ->add('firstNameUser', TextType::class, array('attr' => array('placeholder' => 'John')))
                        ->add('password', PasswordType::class, array('attr' => array('placeholder' => 'Votre mot de passe')))
                        ->add('entreprise', EntityType::class, array('class' => 'App\Entity\Entreprise',
                            'choice_label' => 'nom', 'placeholder' => 'Choisissez votre Entreprise'))
                        ->add('age', IntegerType::class, array('attr' => array('placeholder' => '51')))
                        ->add('niveau', EntityType::class, array('class' => 'App\Entity\NiveauUser',
                            'choice_label' => 'libelle_level_user', 'placeholder' => 'Choisissez votre niveau'))
                        ->add('email', TextType::class, array('attr' => array('placeholder' => 'email@hebergeur.fr')))
                        //->add('payer', EntityType::class, array('class' => 'App\Entity\Payer', 'choice_label' => 'date_deb','mapped'=>false))
                        ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-secondary'),
                            'label' => 'S\'inscrire'))->getForm();
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $user->setRoles(array('ROLE_USER'));
                $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
                $em->persist($user);
                $em->flush();
                return $this->redirectToRoute('accueil');
            }
        } return $this->render('security/signin.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/account", name="account")
     */
    public function account() {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('home/account.html.twig', []);
    }

}
