<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConsultantRecruteursController extends BddController
{
    /**
     *
     * @Route("/consultant/liste/recruteur/{valider}", name="app_consultant_recruteur")
     * @Route("/consultant/voir/recruteur/{valider}/{id}", name="app_consultant_voirrecruteur")
     * IsGranted("ROLE_CONSULTANT")
     * @return Response
     */
    public function recruteur($id = null, $valider = null): Response
    {
        
        $parametres = $this->getListe($id, 'ROLE_RECRUTEUR', 'recruteur', $valider);
        return $this->render(
            'consultant/consultant_recruteurs/consultant_recruteurs.html.twig',
            $parametres

        );
    }

    /**
     *
     *
     * @Route("/consultant/supprimer/recruteur/{id}", name="app_consultant_supprimer_recruteur")
     * IsGranted("ROLE_CONSULTANT")
     * @return Response
     */
    public function suuprimer_recruteur($id = null): Response
    {
        $valider = 'nonvalider';

        $parametres = $this->getListe($id, 'ROLE_RECRUTEUR', 'recruteur', $valider);
        $parametres['suppression'] = true;
        $parametres['fiche'] = false;
        return $this->render(
            'consultant/consultant_recruteurs/consultant_recruteurs.html.twig',
            $parametres

        );
    }

    /**
     *
     *
     * @Route("/consultant/confirmer/supprimer/recruteur/{id}", name="app_consultant_confirmer_supprimer_recruteur")
     * IsGranted("ROLE_CONSULTANT")
     * @return Response
     */
    public function confirmerSupprimerRecruteur($id = null)
    {
        $this->Confirmersupprimerprofil($id);
        return $this->redirectToRoute('app_consultant_recruteur', ['valider' => 'nonvalider']);
    }
}
