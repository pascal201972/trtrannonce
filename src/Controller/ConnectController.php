<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConnectController extends AbstractController
{
    /** 
     * @Route("/connect", name="app_connect")
     * IsGranted("ROLE_USER")
     */
    public function index(): Response
    {
        if ($this->getUser()) {
            $user = $this->getUser();
            $role = $user->getRoles();

            $route = explode('_',  $role[0]);
            $route = 'app_' . strtolower($route[1]);

            return  $this->redirectToRoute($route);
        }
    }
}
