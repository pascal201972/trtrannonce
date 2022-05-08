<?php

namespace App\Controller;

use App\Repository\TrtAnnonceRepository;
use App\Repository\TrtProfessionsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends BddController
{
    /** 
     *  @Route("/", name="app_home")
     */
    public function index(): Response
    {
        $profession = $this->reposProfession->findAll();

        return $this->render('home/home.html.twig', [
            'page' => 'home',
            'profession' => $profession
        ]);
    }
}
