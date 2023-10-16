<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CategoryController extends AbstractController {

    /**
     * @Route("/category_add", name="category_add")
     */ public function add(Request $request) {
        $category = new Categorie();
        $form = $this->createFormBuilder($category)
                ->add('libelle', TextType::class)
                ->add('save', SubmitType::class, array('attr'=>array('class' => 'btn btn-secondary'), 'label' => 'Ajouter'))
                ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($category);
                $em->flush();
                return $this->redirectToRoute('category_list');
            }
        }
        return $this->render('category/add.html.twig', array('form' => $form->createView(),
        ));
    }

    /**
     * @Route("/category_list", name="category_list")
     */ public function liste(Request $request) {
        $repository = $this->getDoctrine()->getManager()->getRepository(Categorie::class);
        $category = new Categorie();
        $form = $this->createFormBuilder($category)
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
        $listCategory = $repository->findAll();

        return $this->render('category/category_list.html.twig', [
                    'listCategory' => $listCategory,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/category_modify/{id}", name="category_modify")
     */
    public function modifier(Request $request) {
        $repository = $this->getDoctrine()->getManager()->getRepository(Categorie::class);
        $category = $repository->find($request->get('id'));
        $form = $this->createFormBuilder($category)
                ->add('libelle', TextType::class)
                ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-secondary'), 'label' => 'Modifier'))
                ->getForm();
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($category);
                $em->flush();

                return $this->redirectToRoute('category_list');
            }
        }
        return $this->render('category/category_modify.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/category_JSON", name="category_JSON")
     */
    public function categoryJSON(Request $request, CategorieRepository $repository) {
        $categorys = $repository->selectAllCategorys();
        return $this->json($categorys);
    }

    /**
     * @Route("/insert_category_android/{libelle}", name="insert_category_android")
     */
    public function insertCategorieAndroid(Request $request, CategorieRepository $repository) {
        $form = array();
        $libelle = $request->get('libelle');
        if ($libelle != null) {  
            $form['result'] = $repository->insertCategorie($libelle) ? "success" : "error";
        }
        return $this->json($form);
    }
}
