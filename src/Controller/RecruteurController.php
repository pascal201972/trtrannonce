<?php

namespace App\Controller;

use App\Form\MdpFormType;
use App\Entity\TrtAnnonce;
use App\Form\FormAnnonceType;
use App\Form\ResetPassEmailType;
use App\Controller\BddController;
use App\Entity\TrtProfilrecruteur;
use App\Form\FormProfilRecruteurType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecruteurController extends BddController
{
    /**
     * 
     * @Route("/recruteur", name="app_recruteur")
     * IsGranted("ROLE_RECRUTEUR")
     * @return Response
     */

    public function index(Request $request): Response
    {
        $route = "app_recruteur";
        $user = $this->getUser();
        $profilRecruteur = $this->reposProfilRecruteur->findOneByUser($user);

        $complet = false;
        if ($profilRecruteur) {

            $complet = $this->isProfilComplet($user);
        } else {
            $profilRecruteur = new TrtProfilrecruteur();
            $complet = false;
        }


        $formemail = $this->createForm(ResetPassEmailType::class);
        $formMdp = $this->createForm(MdpFormType::class);
        $this->formprofil($route, $user, $request, $formemail, $formMdp);

        $formeProfilRecruteur = $this->createForm(FormProfilRecruteurType::class, $profilRecruteur);
        $formeProfilRecruteur->handleRequest($request);
        $complet = $this->isProfilComplet($user);

        $parametres = [
            'page' => 'administration',
            'onglet' => 'profil',
            'formemail' => $formemail->createView(),
            'formMdp' => $formMdp->createView(),
            'formProfilRecruteur' => $formeProfilRecruteur->createView(),
            'complet' => $complet
        ];
        if ($formeProfilRecruteur->isSubmitted() && $formeProfilRecruteur->isValid()) {
            $user->setProfil($complet);
            $profilRecruteur->setIdUser($user);
            $this->entityManager->persist($profilRecruteur);
            $this->entityManager->flush();

            $this->redirectToRoute(
                'app_recruteur'
            );
        }
        return $this->render(
            'recruteur/entreprise.html.twig',
            $parametres

        );
    }

    /**
     * 
     * @Route("/recruteur/annonce", name="app_recruteur_annonce")
     * IsGranted("ROLE_RECRUTEUR")
     * @return Response
     */

    public function listeannonces(Request $request): Response
    {
        $user = $this->getUser();
        $id = null;
        $action = 'aucune';

        $route = $this->getAction($action, $id, $user, $request);
        if ($route['soumis'])  return $this->redirectToRoute('app_recruteur_annonce');
        return $this->render(
            'recruteur/entreprise.html.twig',
            $route['parametres']

        );
    }

    /**
     * 
     * @Route("/recruteur/annonce/supprimer/{id}", name="app_recruteur_annonce_supprimer")
     * IsGranted("ROLE_RECRUTEUR")
     * @return Response
     */
    public function SupprimeAnnonce($id, Request $request)
    {

        $user = $this->getUser();

        $action = 'supprimer';
        $route = $this->getAction($action, $id, $user, $request);
        if ($route['soumis'])  return $this->redirectToRoute('app_recruteur_annonce');
        return $this->render(
            'recruteur/entreprise.html.twig',
            $route['parametres']

        );
    }
    /**
     * 
     * @Route("/recruteur/annonce/supprimer/confirmer/{id}", name="app_recruteur_annonce_supprimer_confirmer")
     * IsGranted("ROLE_RECRUTEUR")
     * @return Response
     */
    public function confirmerSuppressionAnnonce($id, Request $request)
    {
        $annonce = $this->reposAnnonce->findOneBy(['id' => $id]);
        $this->entityManager->remove($annonce);
        $this->entityManager->flush();
        return $this->redirectToRoute('app_recruteur_annonce');
    }
    /**
     * 
     * @Route("/recruteur/annonce/ajouter/", name="app_recruteur_annonce_ajouter")
     * IsGranted("ROLE_RECRUTEUR")
     * @return Response
     */
    public function Ajouter(Request $request)
    {;
        $user = $this->getUser();
        $id = null;
        $action = 'ajouter';
        $route = $this->getAction($action, $id, $user, $request);
        if ($route['soumis'])  return $this->redirectToRoute('app_recruteur_annonce');
        return $this->render(
            'recruteur/entreprise.html.twig',
            $route['parametres']

        );
    }

    /**
     * 
     * @Route("/recruteur/annonce/modifier/{id}", name="app_recruteur_annonce_modifier")
     * IsGranted("ROLE_RECRUTEUR")
     * @return Response
     */
    public function modifierAnnonce($id, Request $request)
    {
        $user = $this->getUser();

        $action = 'modifier';
        $route = $this->getAction($action, $id, $user, $request);
        if ($route['soumis'])  return $this->redirectToRoute('app_recruteur_annonce');
        return $this->render(
            'recruteur/entreprise.html.twig',
            $route['parametres']

        );
    }
    /**
     * 
     * @Route("/recruteur/annonce/voir/{id}", name="app_recruteur_annonce_voir")
     * IsGranted("ROLE_RECRUTEUR")
     * @return Response
     */
    public function voirAnnonce($id, Request $request)
    {

        $user = $this->getUser();

        $action = 'voir';
        $route = $this->getAction($action, $id, $user, $request);
        if ($route['soumis'])  return $this->redirectToRoute('app_recruteur_annonce');
        return $this->render(
            'recruteur/entreprise.html.twig',
            $route['parametres']

        );
    }




    public function setAction($action, $annonce, $profil)
    {
        if ($action == 'ajouter') {
            $ref = 'ann100' . $annonce->getId();
            $annonce->setRef($ref);
            $profil->addAnnonce($annonce);
        }
        $annonce->setRecruteur($profil);
        $annonce->setValider(0);
        $complet = $this->isAnnonceComplet($annonce);
        $annonce->setComplet($complet);
        $this->entityManager->persist($annonce);
        $this->entityManager->persist($profil);
        $this->entityManager->flush();
    }
    public function getParametresRoute($action, $annonce, $profil)
    {
    }


    public function getAction($action, $id, $user, $request)
    {
        $liste = array();
        $profilRecruteur = $this->reposProfilRecruteur->findOneByUser($user);
        $listeprofil = array();
        if ($profilRecruteur) {

            $liste = $profilRecruteur->getAnnonce();
            foreach ($liste as $annonce) {

                $candidat = $this->reposCandidature->findProfilValiderByAnnonce($annonce->getId());

                $listeprofil[$annonce->getId()] = $candidat;
            }
        }

        if ($id == null) {
            $annonce = new TrtAnnonce();
        } else {
            $annonce = $this->reposAnnonce->findOneBy(['id' => $id]);
        }

        $formAnnonce = $this->createForm(FormAnnonceType::class, $annonce);
        $formAnnonce->handleRequest($request);
        if ($user->getValider() == false) {
            $valider = "nonvalider";
        } else $valider = 'valider';
        $parametres = [
            'page' => 'administration',
            'onglet' => 'annonce',
            'formannonce' => $formAnnonce->createView(),
            'liste' => $liste,
            'action' => $action,
            'valider' => $valider,
            'listeCandidats' => $listeprofil
        ];
        $route['soumis'] = false;
        switch ($action) {
            case 'aucune':


                break;
            case 'ajouter':

                if ($formAnnonce->isSubmitted() && $formAnnonce->isValid()) {
                    $this->setAction($action, $annonce, $profilRecruteur);
                    $route['soumis'] = true;
                }

                break;
            case 'modifier':

                if ($formAnnonce->isSubmitted() && $formAnnonce->isValid()) {
                    $this->setAction($action, $annonce, $profilRecruteur);
                    $route['soumis'] = true;
                }
                $parametres['annonce'] = $annonce;
                $this->setAction($action, $annonce, $profilRecruteur);
                break;
            case 'supprimer':
            case 'voir':

                $parametres['annonce'] = $annonce;
                break;
        }


        $route['parametres'] = $parametres;
        return $route;
    }
}
