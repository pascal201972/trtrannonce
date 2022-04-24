<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ErreursController extends AbstractController
{
    #[Route('/erreurs', name: 'app_erreurs')]
    public function index(): Response
    {
        return $this->render('erreurs/index.html.twig', [
            'controller_name' => 'ErreursController',
        ]);
    }
}
