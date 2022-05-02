<?php

namespace App\Controller;

use App\Controller\BddController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmploisController extends BddController
{

    /** 
     * @Route("/emplois/", name= "app_emplois")
     * 
     */
    public function index(): Response
    {
        $listeannonces = $this->reposAnnonce->findBy(['valider' => true]);

        return $this->render('emplois/emplois.html.twig', [
            'page' => 'emplois',
            'liste' => $listeannonces
        ]);
    }
}
