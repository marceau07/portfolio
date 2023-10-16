<?php

namespace App\Controller;

use App\Repository\AbonnementRepository;
use App\Entity\Abonnement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SubscriptionController extends AbstractController {

    /**
     * @Route("/subscription_add", name="subscription_add")
     */ public function add(Request $request) {
        $subscription = new Abonnement();
        $form = $this->createFormBuilder($subscription)
                ->add('libelle', TextType::class)
                ->add('nbFois', IntegerType::class)
                ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-secondary'), 'label' => 'Ajouter'))
                ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($subscription);
                $em->flush();
                return $this->redirectToRoute('subscription_list');
            }
        }
        return $this->render('subscription/add.html.twig', array('form' => $form->createView(),
        ));
    }

    /**
     * @Route("/subscription_list", name="subscription_list")
     */ public function liste(Request $request) {
        $repository = $this->getDoctrine()->getManager()->getRepository(Abonnement::class);
        $subscription = new Abonnement();
        $form = $this->createFormBuilder($subscription)
                ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-danger'), 'label' => 'Supprimer'))
                ->getForm();
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $cocher = $request->request->get('cocher');
                foreach ($cocher as $i) {
                    $u = $repository->find($i);
                    $this->getDoctrine()->getManager()->remove($u);
                }
                $this->getDoctrine()->getManager()->flush();
            }
        }
        $listSubscription = $repository->findAll();
        return $this->render('subscription/subscription_list.html.twig', [
                    'listSubscription' => $listSubscription, 'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/subscription_modify/{id}", name="subscription_modify")
     */
    public function modifier(Request $request) {
        $repository = $this->getDoctrine()->getManager()->getRepository(Abonnement::class);
        $subscription = $repository->find($request->get('id'));
        $form = $this->createFormBuilder($subscription)
                ->add('libelle', TextType::class)
                ->add('nbFois', IntegerType::class)
                ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-secondary'), 'label' => 'Modifier'))
                ->getForm();
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($subscription);
                $em->flush();
                return $this->redirectToRoute('subscription_list');
            }
        }
        return $this->render('subscription/subscription_modify.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/subscription_JSON", name="subscription_JSON")
     */
    public function subscriptionJSON(Request $request, AbonnementRepository $repository) {
        $subscriptions = $repository->selectAllSubscriptions();
        return $this->json($subscriptions);
    }

    /**
     * @Route("/insert_subscription_android/{libelle}/{nb_fois}", name="insert_subscription_android")
     */
    public function insertSubscriptionAndroid(Request $request, AbonnementRepository $repository) {
        $form = array();
        $libelle = $request->get('libelle');
        $nbFois = $request->get('nb_fois');
        if ($libelle != null && $nbFois != null) {  
            $form['result'] = $repository->insertSubscription($libelle, $nbFois) ? "success" : "error";
        }
        return $this->json($form);
    }
}
