<?php

namespace App\Controller;

use App\Entity\Theme;
use App\Repository\ThemeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ThemeController extends AbstractController {

    /**
     * @Route("/theme_add", name="theme_add")
     */ public function add(Request $request) {
        $theme = new Theme();
        $form = $this->createFormBuilder($theme)
                ->add('libelle', TextType::class)
                ->add('save', SubmitType::class, array('attr'=>array('class' => 'btn btn-secondary'), 'label' => 'Ajouter'))
                ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($theme);
                $em->flush();
                return $this->redirectToRoute('theme_list');
            }
        }
        return $this->render('theme/add.html.twig', array('form' => $form->createView(),
        ));
    }

    /**
     * @Route("/theme_list", name="theme_list")
     */ public function liste(Request $request) {
        $repository = $this->getDoctrine()->getManager()->getRepository(Theme::class);
        $theme = new Theme();
        $form = $this->createFormBuilder($theme)
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
        $listTheme = $repository->findAll();
        return $this->render('theme/theme_list.html.twig', [
                    'listTheme' => $listTheme, 'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/theme_modify/{id}", name="theme_modify")
     */
    public function modifier(Request $request) {
        $repository = $this->getDoctrine()->getManager()->getRepository(Theme::class);
        $theme = $repository->find($request->get('id'));
        $form = $this->createFormBuilder($theme)
                ->add('vocabulaires', EntityType::class,array('class'=> 'App\Entity\Vocabulaire',
                    'choice_label' => 'libelle'))
                ->add('libelle', TextType::class)
                ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-secondary'), 'label' => 'Modifier'))
                ->getForm();
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($theme);
                $em->flush();
                return $this->redirectToRoute('theme_list');
            }
        }
        return $this->render('theme/theme_modify.html.twig', ['form' => $form->createView()]);
    }
   /**
     * @Route("/theme_JSON", name="theme_JSON")
     */
    public function themeJSON(Request $request, ThemeRepository $repository) {
        $themes = $repository->selectAllthemes();
        return $this->json($themes);
    }
    
    /**
     * @Route("/insert_theme_android/{libelle}", name="insert_theme_android")
     */
    public function insertThemeAndroid(Request $request, ThemeRepository $repository) {
        $form = array();
        $libelle = $request->get('libelle');
        if ($libelle != null) {  
            $form['result'] = $repository->insertTheme($libelle) ? "success" : "error";
        }
        return $this->json($form);
    }
}
