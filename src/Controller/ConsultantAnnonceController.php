<?php

namespace App\Controller;

use App\Services\EnvoieEmail;
use App\Controller\BddController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConsultantAnnonceController extends BddController
{
    /**
     *
     * @Route("/consultant/liste/annonce/{valider}", name="app_consultant_annonce")
     * 
     * IsGranted("ROLE_CONSULTANT")
     * @return Response
     */
    public function listeAnnonce($valider = null): Response
    {

        $parametres = $this->getannonce(false, $valider);


        return $this->render(
            'consultant/consultant_annonces/consultant_annonce.html.twig',
            $parametres
        );
    }
    /**
     *
     * @Route("/consultant/voir/annonce/{id}", name="app_consultant_annonce_voir")
     * 
     * IsGranted("ROLE_CONSULTANT")
     * @return Response
     */
    public function VoirAnnonce($id = null): Response
    {

        $annonce = $this->reposAnnonce->findOneBy(['id' => $id]);
        if ($annonce->getValider()) $valider = 'valider';
        else $valider = 'nonvalider';
        $parametres = $this->getannonce(true, $valider);
        $parametres['annonce'] = $annonce;
        $parametres['fichelise'] = true;
        return $this->render(
            'consultant/consultant_annonces/consultant_annonce.html.twig',
            $parametres
        );
    }

    /**
     *
     * @Route("/consultant/annonce/supprimer/{id}", name="app_consultant_annonce_supprimer")
     * 
     * IsGranted("ROLE_CONSULTANT")
     * @return Response
     */
    public function SupprimerAnnonce($id)
    {
        $valider = 'nonvalider';
        $parametres = $this->getannonce(false, $valider);
        $annonce = $this->reposAnnonce->findOneBy(['id' => $id]);
        $parametres['annonce'] = $annonce;
        $parametres['suppression'] = true;

        return $this->render(
            'consultant/consultant_annonces/consultant_annonce.html.twig',
            $parametres
        );
    }
    /**
     *
     * @Route("/consultant/annonce/supprimer/confirmer/{id}", name="app_consultant_annonce_supprimer_confirmer")
     * 
     * IsGranted("ROLE_CONSULTANT")
     * @return Response
     */
    public function ConfirmerSupprimerAnnonce($id)
    {
        $candidatures = $this->reposCandidature->findBy(['annonce' => $id]);
        if ($candidatures != null) {
            foreach ($candidatures as $cdt) {
                $this->entityManager->remove($cdt);
                $this->entityManager->flush();
            }
        }
        $annonce = $this->reposAnnonce->findOneBy(['id' => $id]);
        $this->entityManager->remove($annonce);
        $this->entityManager->flush();
        return $this->redirectToRoute('app_consultant_annonce', ['valider' => 'nonvalider']);
    }
    /**
     *
     * @Route("/consultant/liste/annonce/valider/{id}", name="app_consultant_annonce_validation")
     * 
     * IsGranted("ROLE_CONSULTANT")
     * @return Response
     */

    public function ValiderAnnonce($id)
    {

        $annonce = $this->reposAnnonce->findOneBy(['id' => $id]);
        //  si l'annonce est complète
        if ($annonce->getComplet()) {
            $annonce->setValider(true);
            $this->entityManager->persist($annonce);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_consultant_annonce', ['valider' => 'nonvalider']);
        }
    }
    /**
     *
     * @Route("/consultant/liste/annonce/desactiver/{id}", name="app_consultant_annonce_desactivation")
     * IsGranted("ROLE_CONSULTANT")
     * @return Response
     */
    public function desactiverAnnonce($id)
    {
        // vrifier si une annonce a des candidatures
        $listecandidature = $this->reposCandidature->findBy(['annonce' => $id]);
        if (!$listecandidature) {
            if ($id != null) {
                $annonce = $this->reposAnnonce->findOneBy(['id' => $id]);
                if ($annonce) {
                    $annonce->setValider(false);
                    $this->entityManager->persist($annonce);
                    $this->entityManager->flush();
                }
            }
        }


        return $this->redirectToRoute('app_consultant_annonce', ['valider' => 'valider']);
    }



    /**
     *
     * @Route("/consultant/liste/annonce/candidature/{id}", name="app_consultant_annonce_candidature_valider")
     * IsGranted("ROLE_CONSULTANT")
     * @return Response
     */
    public function validercandidature($id, EnvoieEmail $envoieEmail)
    {
        $valider = 'valider';
        $parametres = $this->getannonce(false, $valider);

        if ($id != null) {
            $cdt = $this->reposCandidature->findOneBy(['id' => $id]);
            if ($cdt) {
                $cdt->setValider(true);
                $this->entityManager->persist($cdt);
                $this->entityManager->flush();
                $subject = "un candidat pour votre annonce.";
                $template = 'templateEmail/email_candidature.html.twig';

                $candidature = $this->reposCandidature->findEmailUserBycandidature($id);
                $email = $candidature['email'];
                $context = [
                    'recruteur' => $candidature['recruteur'],
                    'profession' => $candidature['profession'],
                    'contrat' => $candidature['contrat'],
                    'experience' => $candidature['experience']

                ];
                $envoieEmail->SendEmail($email, $subject, $template, $context);
                // $this->addFlash('successEmail', "Un email vient de vous être envoyé.");
            }
        }
        return $this->redirectToRoute('app_consultant_annonce',  $parametres);
    }
    /**
     *
     * @Route("/consultant/liste/annonce/candidature/validation/supprimer/{id}", name="app_consultant_annonce_candidature_supprimer_validation")
     * IsGranted("ROLE_CONSULTANT")
     * @return Response
     */
    public function supprimerValidationcandidature($id)
    {
        $valider = 'valider';
        $parametres = $this->getannonce(false, $valider);
        if ($id != null) {
            $cdt = $this->reposCandidature->findOneBy(['id' => $id]);
            if ($cdt) {
                $cdt->setValider(false);
                $this->entityManager->persist($cdt);
                $this->entityManager->flush();
            }
        }
        return $this->redirectToRoute('app_consultant_annonce',  $parametres);
    }

    /**
     *
     * @Route("/consultant/liste/annonce/candidature/supprimer/{id}", name="app_consultant_annonce_candidature_supprimer")
     * IsGranted("ROLE_CONSULTANT")
     * @return Response
     */

    public function supprimercandidature($id)
    {
        $valider = 'valider';
        $parametres = $this->getannonce(false, $valider);
        if ($id != null) {
            $cdt = $this->reposCandidature->findOneBy(['id' => $id]);
            if ($cdt) {

                $this->entityManager->remove($cdt);
                $this->entityManager->flush();
            }
        }
        return $this->redirectToRoute('app_consultant_annonce',  $parametres);
    }
}
