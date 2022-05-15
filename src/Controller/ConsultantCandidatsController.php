<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConsultantCandidatsController extends BddController
{
    /**
     * 
     * @Route("/consultant/liste/candidat/{valider}", name="app_consultant_candidat")
     * @Route("/consultant/voir/candidat/{valider}/{id}", name="app_consultant_voircandidat")
     * IsGranted("ROLE_CONSULTANT")
     * @return Response
     */
    public function candidat($id = null, $valider = null): Response
    {

        $parametres = $this->getListe($id, 'ROLE_CANDIDAT', 'candidat', $valider);

        return $this->render(
            'consultant/consultant_candidats/consultant_candidats.html.twig',
            $parametres

        );
    }

    /**
     *
     *
     * @Route("/consultant/supprimer/candidat/{id}", name="app_consultant_supprimer_candidat")
     * IsGranted("ROLE_CONSULTANT")
     * @return Response
     */
    public function suuprimer_candidat($id = null): Response
    {
        $valider = 'nonvalider';

        $parametres = $this->getListe($id, 'ROLE_CANDIDAT', 'candidat', $valider);
        $parametres['suppression'] = true;
        $parametres['fiche'] = false;
        return $this->render(
            'consultant/consultant_candidats/consultant_candidats.html.twig',
            $parametres

        );
    }
    /**
     *
     *
     * @Route("/consultant/confirmer/supprimer/candidat/{id}", name="app_consultant_confirmer_supprimer_candidat")
     * IsGranted("ROLE_CONSULTANT")
     * @return Response
     */
    public function confirmerSupprimerCandidat($id = null)
    {
        $this->Confirmersupprimerprofil($id);
        return $this->redirectToRoute('app_consultant_candidat', ['valider' => 'nonvalider']);
    }
}
