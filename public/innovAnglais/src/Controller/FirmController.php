<?php

namespace App\Controller;

use App\Repository\EntrepriseRepository;
use App\Entity\Entreprise;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FirmController extends AbstractController {

    /**
     * @Route("/firm_add", name="firm_add")
     */ public function add(Request $request) {
        $firm = new Entreprise();
        $form = $this->createFormBuilder($firm)
                ->add('nom', TextType::class)
                ->add('save', SubmitType::class, array('attr'=>array('class' => 'btn btn-secondary'), 'label' => 'Ajouter'))
                ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($firm);
                $em->flush();
                return $this->redirectToRoute('firm_list');
            }
        }
        return $this->render('firm/add.html.twig', array('form' => $form->createView(),
        ));
    }

    /**
     * @Route("/firm_list", name="firm_list")
     */ public function liste(Request $request) {
        $repository = $this->getDoctrine()->getManager()->getRepository(Entreprise::class);
        $firm = new Entreprise();
        $form = $this->createFormBuilder($firm)
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
        $listFirm = $repository->findAll();
        return $this->render('firm/firm_list.html.twig', [
                    'listFirm' => $listFirm,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/firm_modify/{id}", name="firm_modify")
     */
    public function modifier(Request $request) {
        $repository = $this->getDoctrine()->getManager()->getRepository(Entreprise::class);
        $firm = $repository->find($request->get('id'));
        $form = $this->createFormBuilder($firm)
                ->add('nom', TextType::class)
                ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-secondary'), 'label' => 'Modifier'))
                ->getForm();
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($firm);
                $em->flush();
                return $this->redirectToRoute('firm_list');
            }
        }
        return $this->render('firm/firm_modify.html.twig', ['form' => $form->createView()]);
    }
    
    /**
     * @Route("/firms_JSON", name="firms_JSON")
     */
    public function firmJSON(Request $request, EntrepriseRepository $repository) {
        $firms = $repository->selectAllFirms();
        return $this->json($firms);
    }

    /**
     * @Route("/insert_firm_android/{nom}", name="insert_firm_android")
     */
    public function insertFirmAndroid(Request $request, EntrepriseRepository $repository) {
        $form = array();
        $nom = $request->get('nom');
        if ($nom != null) {  
            $form['result'] = $repository->insertFirm($nom) ? "success" : "error";
        }
        return $this->json($form);
    }
}
