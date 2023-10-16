<?php

namespace App\Controller;

use App\Repository\NiveauRepository;
use App\Entity\Niveau;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class LevelController extends AbstractController {

    /**
     * @Route("/level_add", name="level_add")
     */ public function add(Request $request) {
        $level = new Niveau();
        $form = $this->createFormBuilder($level)
                ->add('libelle', TextType::class)
                ->add('save', SubmitType::class, array('attr'=>array('class' => 'btn btn-secondary'), 'label' => 'Ajouter'))
                ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($level);
                $em->flush();
                return $this->redirectToRoute('level_list');
            }
        }
        return $this->render('level/add.html.twig', array('form' => $form->createView(),
        ));
    }

    /**
     * @Route("/level_list", name="level_list")
     */ public function liste(Request $request) {
        $repository = $this->getDoctrine()->getManager()->getRepository(Niveau::class);
        $level = new Niveau();
        $form = $this->createFormBuilder($level)
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
        $listLevel = $repository->findAll();
        return $this->render('level/level_list.html.twig', [
                    'listLevel' => $listLevel, 'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/level_modify/{id}", name="level_modify")
     */
    public function modifier(Request $request) {
        $repository = $this->getDoctrine()->getManager()->getRepository(Niveau::class);
        $level = $repository->find($request->get('id'));
        $form = $this->createFormBuilder($level)
                ->add('libelle', TextType::class)
                ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-secondary'), 'label' => 'Modifier'))
                ->getForm();
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($level);
                $em->flush();
                return $this->redirectToRoute('level_list');
            }
        }
        return $this->render('level/level_modify.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/level_JSON", name="level_JSON")
     */
    public function levelJSON(Request $request, NiveauRepository $repository) {
        $levels = $repository->selectAllLevels();
        return $this->json($levels);
    }

    /**
     * @Route("/insert_level_android/{libelle}", name="insert_level_android")
     */
    public function insertCategorieAndroid(Request $request, NiveauRepository $repository) {
        $form = array();
        $libelle = $request->get('libelle');
        if ($libelle != null) {  
            $form['result'] = $repository->insertLevel($libelle) ? "success" : "error";
        }
        return $this->json($form);
    }
}
