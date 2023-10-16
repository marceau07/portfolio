<?php

namespace App\Controller;

use App\Entity\Date;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DateController extends AbstractController {

    /**
     * @Route("/date_add", name="date_add")
     */ public function add(Request $request) {
        $date = new Date();
        $form = $this->createFormBuilder($date)
                ->add('date', DateType::class)
                ->add('save', SubmitType::class, array('attr'=>array('class' => 'btn btn-secondary'), 'label' => 'Ajouter'))
                ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($date);
                $em->flush();
                return $this->redirectToRoute('date_list');
            }
        }
        return $this->render('date/add.html.twig', array('form' => $form->createView(),
        ));
    }

    /**
     * @Route("/date_list", name="date_list")
     */ public function liste(Request $request) {
        $repository = $this->getDoctrine()->getManager()->getRepository(Date::class);
        $date = new Date();
        $form = $this->createFormBuilder($date)
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
        $listDate = $repository->findAll();
        return $this->render('date/date_list.html.twig', [
                    'listDate' => $listDate,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/date_modify/{id}", name="date_modify")
     */
    public function modifier(Request $request) {
        $repository = $this->getDoctrine()->getManager()->getRepository(Date::class);
        $date = $repository->find($request->get('id'));
        $form = $this->createFormBuilder($date)
                ->add('date', DateType::class)
                ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-secondary'), 'label' => 'Modifier'))
                ->getForm();
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($date);
                $em->flush();
                return $this->redirectToRoute('date_list');
            }
        }
        return $this->render('date/date_modify.html.twig', ['form' => $form->createView()]);
    }

}
