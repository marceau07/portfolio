<?php

namespace App\Controller;

use App\Repository\VocabulaireRepository;
use App\Entity\Vocabulaire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class VocabularyController extends AbstractController {

    /**
     * @Route("/vocabulary_add", name="vocabulary_add")
     */ public function add(Request $request) {
        $vocabulary = new Vocabulaire();
        $form = $this->createFormBuilder($vocabulary)
                ->add('categorie', EntityType::class, array(
                    'class' => 'App\Entity\Categorie',
                    'choice_label' => 'libelle'))
                ->add('theme', EntityType::class, array(
                    'class' => 'App\Entity\Theme',
                    'choice_label' => 'libelle'))
                ->add('libelle', TextType::class)
                ->add('save', SubmitType::class, array('attr'=>array('class' => 'btn btn-secondary'), 'label' => 'Ajouter'))
                ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($vocabulary);
                $em->flush();
                return $this->redirectToRoute('vocabulary_list');
            }
        }
        return $this->render('vocabulary/add.html.twig', array('form' => $form->createView(),
        ));
    }

    /**
     * @Route("/vocabulary_list", name="vocabulary_list")
     */ public function liste(Request $request) {
        $repository = $this->getDoctrine()->getManager()->getRepository(Vocabulaire::class);
        $vocabulary = new Vocabulaire();
        $form = $this->createFormBuilder($vocabulary)
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
        $listVocabulary = $repository->findAll();
        return $this->render('vocabulary/vocabulary_list.html.twig', [
                    'listVocabulary' => $listVocabulary, 'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/vocabulary_modify/{id}", name="vocabulary_modify")
     */
    public function modifier(Request $request) {
        $repository = $this->getDoctrine()->getManager()->getRepository(Vocabulaire::class);
        $vocabulary = $repository->find($request->get('id'));
        $$form = $this->createFormBuilder($vocabulary)
                ->add('categorie', IntegerType::class)
                ->add('theme', IntegerType::class)
                ->add('libelle', TextType::class)
                ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-secondary'), 'label' => 'Modifier'))
                ->getForm();
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($vocabulary);
                $em->flush();
                return $this->redirectToRoute('vocabulary_list');
            }
        }
        return $this->render('vocabulary/vocabulary_modify.html.twig', ['form' => $form->createView()]);
    }
    
            /**
     * @Route("/vocabulary_JSON", name="vocabulary_JSON")
     */
    public function vocabularyJSON(Request $request, VocabulaireRepository $repository) {
        $vocabularys = $repository->selectAllVocabularys();
        return $this->json($vocabularys);
    }

}
