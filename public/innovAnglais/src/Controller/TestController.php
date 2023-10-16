<?php

namespace App\Controller;

use App\Repository\TestRepository;
use App\Entity\Test;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class TestController extends AbstractController {

    /**
     * @Route("/test_add", name="test_add")
     */ public function add(Request $request) {
        $test = new Test();
        $form = $this->createFormBuilder($test)
                ->add('theme', EntityType::class, array(
                    'class' => 'App\Entity\Theme',
                    'choice_label' => 'libelle'))
                ->add('niveau', EntityType::class, array(
                    'class' => 'App\Entity\Niveau',
                    'choice_label' => 'libelle'))
                ->add('libelle', TextType::class)
                ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-secondary'), 'label' => 'Ajouter'))
                ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($test);
                $em->flush();
                return $this->redirectToRoute('test_list');
            }
        }
        return $this->render('test/add.html.twig', array('form' => $form->createView(),));
    }

    /**
     * @Route("/test_list", name="test_list")
     */ public function liste(Request $request) {
        $repository = $this->getDoctrine()->getManager()->getRepository(Test::class);
        $test = new Test();
        $form = $this->createFormBuilder($test)
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
        $listTest = $repository->findAll();
        return $this->render('test/test_list.html.twig', ['listTest' => $listTest, 'form' => $form->createView(),]);
    }

    /**
     * @Route("/test", name="test")
     */ public function test(Request $request) {
        $repository = $this->getDoctrine()->getManager()->getRepository(Test::class);
        $test = new Test();
        $form = $this->createFormBuilder($test)
                ->add('save', SubmitType::class, array('attr' => array('class' => 'save'), 'label' => 'Supprimer'))
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
        $listTest = $repository->findAll();
        $listTheme = $repository->selectAllTheme();
        return $this->render('test/test.html.twig', ['listTest' => $listTest, 'listTheme' => $listTheme, 'form' => $form->createView(),]);
    }

    /**
     * @Route("/test_theme/{id}", name="test_theme")
     */ public function test_theme(Request $request) {
        $repository = $this->getDoctrine()->getManager()->getRepository(Test::class);
        $listTestByTheme = $repository->selectTestByTheme($request->get('id'));
        $themeName = $repository->selectThemeName($request->get('id'));
        $levels = $repository->selectAllLevels();

        return $this->render('test/test_theme.html.twig', ['listTest' => $listTestByTheme, 'themeName' => $themeName, 'levels' => $levels, 'themeId' => $request->get('id')]);
    }

    /**
     * @Route("/test_do/{id}", name="test_do")
     */
    public function test_do(Request $request, TestRepository $repository, \App\Repository\EffectuerRepository $effectuer) {
        $get = explode(',', $request->get('id'));
        $dateDebut = date("Y-m-d H:00:00");
        $fake_words = json_decode(file_get_contents('../src/Fichiers/fake_words.json'), true);
        
        $fake_words = $fake_words[0][$get[0]]['niveaux'][$get[1]]['categories'][$get[2]];
        $leTest = $repository->find($get[3]);
        $id = $leTest->getId();
        $testes = $repository->selectLeTest($id);
        $effectuer->insertEffectuer($id, $get[4], $dateDebut);
        $form['effectuer'] = $effectuer->selectEffectuer($id, $get[4], $dateDebut);
        $level = $get[1];
        return $this->render('test/test_do.html.twig', ['form' => $form, 'testes' => $testes, 'fakeWords' => $fake_words, 'dateDebut' => $dateDebut, 'level' => $level]);
    }

    /**
     * @Route("/test_modify/{id}", name="test_modify")
     */
    public function modifier(Request $request) {
        $repository = $this->getDoctrine()->getManager()->getRepository(Test::class);
        $test = $repository->find($request->get('id'));
        $form = $this->createFormBuilder($test)
                ->add('theme', TextType::class)
                ->add('niveau', TextType::class)
                ->add('libelle', TextType::class)
                ->add('entreprise', TextType::class)
                ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-secondary'), 'label' => 'Modifier'))
                ->getForm();
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($test);
                $em->flush();
                return $this->redirectToRoute('test_list');
            }
        }
        return $this->render('test/test_modify.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/test_JSON", name="test_JSON")
     */
    public function testJSON(TestRepository $repository) {
        $tests = $repository->selectAllTests();        
        return $this->json($tests);
    }

    /**
     * @Route("/test_complete", name="test_complete")
     */
    public function test_complete(Request $request, \App\Repository\EffectuerRepository $repository) {
        $testComplete = $request->request->get('testComplete');
        if($testComplete) {
            $test_id = $request->request->get('test_id');
            $user_id = $request->request->get('user_id');
            $date = $request->request->get('date');
            $resultat = $request->request->get('resultat');
            $temps = $request->request->get('temps');
            $repository->updateEffectuer($test_id, $user_id, $date, $resultat, $temps);
        }
        exit();
    }

    /**
     * @Route("/test_complete_android/{test}", name="test_complete_android")
     */
    public function test_complete_android(Request $request, TestRepository $repository, \App\Repository\EffectuerRepository $effectuer) {
        $get = explode(',', $request->get('test'));
        $test = $repository->find($get[3]);
        $test_id = $test->getId();
        $effectuer->updateEffectuer($test_id, $get[4], date('Y-m-d H:00:00'), $get[5], null);
        
        exit();
    }
    
    /**
     * @Route("/test_do_android/{id}", name="test_do_android")
     */
    public function test_do_android(Request $request, TestRepository $repository, \App\Repository\EffectuerRepository $effectuer) {
        $get = explode(',', $request->get('id'));
        $dateDebut = date("Y-m-d H:00:00");
        $fake_words = json_decode(file_get_contents('../src/Fichiers/fake_words.json'), true);

        $leTest = $repository->find($get[3]);
        $id = $leTest->getId();
        $tests = $repository->selectLeTest($id);
        $effectuer->insertEffectuer($id, $get[4], $dateDebut);
        $form['effectuer'] = $effectuer->selectEffectuer($id, $get[4], $dateDebut);
        $level = $get[1];
        $tests = $tests[0]; 
       
        
//        dd($tests);
        
        return $this->json(array([
            "test_id"                   => $tests['idTest'],
            "test_label"                => $tests['libelleTest'],
            "level_id"                  => $tests['idNiveau'],
            "level_label"               => $tests['libelleNiveau'],
            "theme_label"               => $tests['libelleTheme'],
            "question_1_label"          => $tests['question_1_label'],
            "question_1_fake_words_1"   => (explode(",", $tests['question_1_fake_words'])[0]),
            "question_1_fake_words_2"   => (explode(",", $tests['question_1_fake_words'])[1]),
            "question_1_answer"         => $tests['question_1_answer'],
            "question_2_label"          => $tests['question_2_label'],
            "question_2_fake_words_1"   => (explode(",", $tests['question_2_fake_words'])[0]),
            "question_2_fake_words_2"   => (explode(",", $tests['question_2_fake_words'])[1]),
            "question_2_answer"         => $tests['question_2_answer'],
            "question_3_label"          => $tests['question_3_label'],
            "question_3_fake_words_1"   => (explode(",", $tests['question_3_fake_words'])[0]),
            "question_3_fake_words_2"   => (explode(",", $tests['question_3_fake_words'])[1]),
            "question_3_answer"         => $tests['question_3_answer'],
            "question_4_label"          => $tests['question_4_label'],
            "question_4_fake_words_1"   => (explode(",", $tests['question_4_fake_words'])[0]),
            "question_4_fake_words_2"   => (explode(",", $tests['question_4_fake_words'])[1]),
            "question_4_answer"         => $tests['question_4_answer']
        ]));
    }
//
//    /**
//     * @Route("/teste_JSON/{id}", name="teste_JSON")
//     */
//    public function testeJSON(Request $request, TestRepository $repository) {
//        $leTest = $repository->find($request->get('id'));
//        $id = $leTest->getId();
//        $testes = $repository->selectLeTest($id);
//        return $this->render('test/test_do.html.twig', ['leTest' => $testes]);
//    }

}
