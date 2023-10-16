<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ToDoController extends AbstractController
{
    /**
     * @Route("/to/do", name="to_do")
     */
    public function index()
    {
        return $this->render('to_do/index.html.twig', [
            'controller_name' => 'ToDoController',
        ]);
    }
}
