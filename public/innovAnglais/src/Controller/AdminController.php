<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\LoginAttempt;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(Request $request)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository(LoginAttempt::class);
        $listBannedIps = $repository->findAll();

        return $this->render('admin/index.html.twig', [
            'listBannedIps' => $listBannedIps,
        ]);
    }
}
