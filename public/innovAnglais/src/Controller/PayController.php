<?php

namespace App\Controller;

use App\Repository\PayerRepository;
use App\Entity\Payer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PayController extends AbstractController {

    /**
     * @Route("/pay/{price}", name="pay")
     */ public function pay(Request $request) {

        $form = array();

        $prices = [5, 10, 15];
        $plan = array(
            ['price' => '5', 'name' => 'Offre Basic', 'price_id' => 'price_HHzw2KHwQTlkrI'],
            ['price' => '10', 'name' => 'Offre Standard', 'price_id' => 'price_HHzw7T4Xrmw6ZN'],
            ['price' => '15', 'name' => 'Offre Premium', 'price_id' => 'price_HHzyWHld598exQ']
        );
        $price = $request->get('price');
        $form['price'] = $price;
        if (!in_array($price, $prices)) {
            return $this->redirectToRoute('index');
        } else {
            switch ($price) {
                case 5:
                    $selectedPlan = $plan[0];
                    break;
                case 10:
                    $selectedPlan = $plan[1];
                    break;
                case 15:
                    $selectedPlan = $plan[2];
                    break;
            }
            // Nous instancions Stripe en indiquand la clé privée, pour prouver que nous sommes bien à l'origine de cette demande
            \Stripe\Stripe::setApiKey('sk_test_q73W8XGY1Kg0Y9f8U7UjH7ek00AyTfv2zc');
            $coupons = \Stripe\Coupon::all(['limit' => 3]);
//            echo json_encode($coupons['data'][0]['name']);
            $intent = \Stripe\Subscription::create([
                'customer' => 'cus_HHzvkYywPeOzPI',
                'items' => [['plan' => $selectedPlan['price_id']]],
                'coupon' => $coupons['data'][0]
            ]);
        }

        return $this->render('pay/index.html.twig', array('form' => $form));
    }

    /**
     * @Route("/pay_add", name="pay_add")
     */ public function add(Request $request) {
        $pay = new Payer();
        $form = $this->createFormBuilder($pay)
                ->add('abonnement', EntityType::class, array('class' => 'App\Entity\Abonnement', 'choice_label' => 'libelle', 'placeholder' => 'Choisir un abonnement'))
                ->add('date_deb', DateType::class)
                ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-secondary'), 'label' => 'Ajouter'))
                ->getForm();


        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $date_deb = $pay->getDateDeb();
                $nb_fois = $pay->getAbonnement()->getNbFois();
                $date_fin = date("Y-m-d", strtotime("+" . $nb_fois . " month"));
                $pay->setDateFin(new \DateTime($date_fin));
                var_dump($date_fin);
                $em->persist($pay);
                $em->flush();
                return $this->redirectToRoute('pay_list');
            }
        }
        return $this->render('pay/add.html.twig', array('form' => $form->createView(),
        ));
    }

    /**
     * @Route("/pay_list", name="pay_list")
     */ public function liste(Request $request) {
        $repository = $this->getDoctrine()->getManager()->getRepository(Payer::class);
        $pay = new Payer();
        $form = $this->createFormBuilder($pay)
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
        $listPay = $repository->findAll();
        return $this->render('pay/pay_list.html.twig', [
                    'listPay' => $listPay, 'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/pay_modify/{id}", name="pay_modify")
     */
    public function modifier(Request $request) {
        $repository = $this->getDoctrine()->getManager()->getRepository(Payer::class);
        $pay = $repository->find($request->get('id'));
        $form = $this->createFormBuilder($pay)
                ->add('libelle', TextType::class)
                ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-secondary'), 'label' => 'Modifier'))
                ->getForm();
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($pay);
                $em->flush();
                return $this->redirectToRoute('pay_list');
            }
        }
        return $this->render('pay/pay_modify.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/pay_JSON", name="pay_JSON")
     */
    public function payJSON(Request $request, PayerRepository $repository) {
        $pays = $repository->selectAllPays();
        return $this->json($pays);
    }

}
