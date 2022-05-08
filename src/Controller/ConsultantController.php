<?php

namespace App\Controller;

use App\Form\MdpFormType;
use App\Services\EnvoieEmail;
use App\Form\ResetPassEmailType;
use App\Controller\BddController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\TrtProfilcandidatRepository;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TrtProfilrecruteurRepository;

class ConsultantController extends BddController
{
    /**
     * 
     * @Route("/consultant", name="app_consultant")
     *
     * IsGranted("ROLE_CONSULTANT")
     * @return Response
     */
    public function index(Request $request): Response
    {
        $route = "app_consultant";
        $user = $this->getUser();
        $formemail = $this->createForm(ResetPassEmailType::class);
        $formMdp = $this->createForm(MdpFormType::class);
        $this->formprofil($route, $user, $request, $formemail, $formMdp);
        return $this->render(
            'consultant/consultant.html.twig',
            [
                'onglet' => 'profil',
                'formemail' => $formemail->createView(),
                'formMdp' => $formMdp->createView()
            ]
        );
    }
    /**
     * 
     * @Route("/consultant/liste/candidat", name="app_consultant_candidat")
     * @Route("/consultant/voir/candidat/{id}", name="app_consultant_voircandidat")
     * IsGranted("ROLE_CONSULTANT")
     * @return Response
     */
    public function candidat($id = null): Response
    {
        $parametres = $this->getListe($id, 'ROLE_CANDIDAT', 'candidat');

        return $this->render(
            'consultant/consultant.html.twig',
            $parametres

        );
    }
    /**
     *
     * @Route("/consultant/liste/recruteur", name="app_consultant_recruteur")
     * @Route("/consultant/voir/recruteur/{id}", name="app_consultant_voirrecruteur")
     * IsGranted("ROLE_CONSULTANT")
     * @return Response
     */
    public function recruteur($id = null): Response
    {
        $parametres = $this->getListe($id, 'ROLE_RECRUTEUR', 'recruteur');
        return $this->render(
            'consultant/consultant.html.twig',
            $parametres

        );
    }
    /**
     * 
     * @Route("/consultant/validation/{id}", name="app_consultant_validation")
     * IsGranted("ROLE_CONSULTANT")
     * @return Response
     */
    public function Validation($id)
    {

        $user = $this->reposUser->findOneBy(['id' => $id]);

        $profil = $this->btn_validation($user, 1);

        return $this->redirectToRoute('app_consultant_' . $profil, ['onglet' => $profil]);
    }

    /**
     * 
     * @Route("/consultant/desactivation/{id}", name="app_consultant_desactivation")
     * IsGranted("ROLE_CONSULTANT")
     * @return Response
     */
    public function desactivation($id)
    {
        $user = $this->reposUser->findOneBy(['id' => $id]);
        $profil = $this->getProfilUser($user);
        if ($profil == 'candidat') {
            $candidature = $this->getListeCandidature($user);
            if (!$candidature) $profil = $this->btn_validation($user, 0);
        }
        if ($profil == 'recruteur') {
            $profiluser = $user->getTrtProfilrecruteur();
            $annonces = $this->reposAnnonce->findBy(['recruteur' => $user->getTrtProfilrecruteur()->getId()]);
            if (!$annonces) $profil = $this->btn_validation($user, 0);
        }
        return $this->redirectToRoute('app_consultant_' . $profil, ['onglet' => $profil]);
    }

    /**
     * 
     * @Route("/consultant/supprimer/{id}", name="app_consultant_supprimer_profil")
     * IsGranted("ROLE_CONSULTANT")
     * @return Response
     */
    public function supprimerprofil($id)
    {
        $user = $this->reposUser->findOneBy(['id' => $id]);
        $profil = $this->getProfilUser($user);
        if ($user) {
            if (!$user->getValider()) {
                if ($profil == 'candidat') {
                    $profiluser = $user->getTrtProfilcandidat();
                    $candidature = $this->reposCandidature->findBy(['profil' => $profiluser->getId()]);
                    foreach ($candidature as $cdt) {
                        $this->entityManager->remove($cdt);
                    }
                    $this->entityManager->flush();
                }

                if ($profil == 'recruteur') {
                    $profiluser = $user->getTrtProfilrecruteur();
                    $annonces = $this->reposAnnonce->findBy(['recruteur' => $profiluser]);
                    foreach ($annonces as $ann) {
                        $this->entityManager->remove($ann);
                    }
                    $this->entityManager->flush();
                }

                $this->entityManager->remove($profiluser);
                $this->entityManager->remove($user);
                $this->entityManager->flush();
            }
        }
        return $this->redirectToRoute('app_consultant_' . $profil, ['onglet' => $profil]);
    }


    /**
     *
     * @Route("/consultant/liste/annonce", name="app_consultant_annonce")
     * 
     * IsGranted("ROLE_CONSULTANT")
     * @return Response
     */
    public function listeAnnonce(): Response
    {
        $parametres = $this->getannonce(false);


        return $this->render(
            'consultant/consultant.html.twig',
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
        $parametres = $this->getannonce(true);

        $annonce = $this->reposAnnonce->findOneBy(['id' => $id]);
        $parametres['annonce'] = $annonce;
        return $this->render(
            'consultant/consultant.html.twig',
            $parametres
        );
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
            return $this->redirectToRoute('app_consultant_annonce');
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


        return $this->redirectToRoute('app_consultant_annonce');
    }



    /**
     *
     * @Route("/consultant/liste/annonce/candidature/{id}", name="app_consultant_annonce_candidature_valider")
     * IsGranted("ROLE_CONSULTANT")
     * @return Response
     */
    public function validercandidature($id, EnvoieEmail $envoieEmail)
    {
        $parametres = $this->getannonce(false);
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
        $parametres = $this->getannonce(false);
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
        $parametres = $this->getannonce(false);
        if ($id != null) {
            $cdt = $this->reposCandidature->findOneBy(['id' => $id]);
            if ($cdt) {

                $this->entityManager->remove($cdt);
                $this->entityManager->flush();
            }
        }
        return $this->redirectToRoute('app_consultant_annonce',  $parametres);
    }



    private function getannonce($fiche)
    {
        $listeNonValides = $this->reposAnnonce->findBy(['valider' => false]);
        $listeValides = $this->reposAnnonce->findBy(['valider' => true]);
        $liste = array();
        $listecandidat = array();
        $listeValide = array();
        foreach ($listeValides as $annonce) {
            $liste['annonce'] = $annonce;
            $listecandidat[$annonce->getId()] = array();
            $candidat = $this->reposCandidature->findProfilcandidatureByAnnonce($annonce->getId());

            $listecandidat[$annonce->getId()] = $candidat;

            $listeValide[] = $liste;
        }
        $parametre = [
            'onglet' => 'annonce',
            'fiche' => $fiche,
            'listenonvalid' => $listeNonValides,
            'listevalid' => $listeValide,
            'listeCandidat' => $listecandidat
        ];
        return $parametre;
    }

    private function btn_validation($user, $etat)
    {


        $profil = $this->getProfilUser($user);

        if ($this->setProfilComplet($profil, $user)) {
            $user->setValider($etat);
        } else $user->setValider(false);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $profil;
    }

    private  function getListe($id = null, $profilrole, $onglet)
    {
        $userprofil = null;
        $fiche = false;
        $profil = "";
        if ($id != null) {
            $userprofil = $this->reposUser->findOneBy(["id" => $id]);

            $profil = $this->getProfilUser($userprofil);

            $fiche = true;
        }
        $listes = $this->getUserRole($profilrole);
        $candidature = array();
        foreach ($listes as $u) {
            $profil = $this->getProfilUser($u);
            switch ($profil) {
                case 'candidat':

                    if ($this->getListeCandidature($u)) {
                        $candidature[$u->getId()] = true;
                    } else  $candidature[$u->getId()] = false;
                    break;
                case 'recruteur':
                    if ($this->getListeAnnonceRecruteur($u)) {
                        $candidature[$u->getId()] = true;
                    } else  $candidature[$u->getId()] = false;
                    break;
            }
        }

        return $parametres = [
            'page' => 'administration',
            'onglet' => $onglet,
            'listes' => $listes,
            'userprofil' => $userprofil,
            'profil' => $profil,
            'fiche' => $fiche,
            'candidature' => $candidature

        ];
    }
    public function getListeCandidature($user)
    {

        $profiluser = $this->reposProfilCdt->findOneByUser($user);


        return   $this->reposCandidature->findBy(['profil' => $profiluser->getId()]);
    }

    public function getProfilUser($userprofil)
    {
        $roles = $userprofil->getRoles();
        $role = $roles[0];
        $profiles = explode('_', $role);
        return   $profil = strtolower($profiles[1]);
    }

    public function getListeAnnonceRecruteur($user)
    {
        $profiluser = $this->reposProfilRecruteur->findOneByUser($user);
        return $this->reposAnnonce->findBy(['recruteur' => $profiluser]);
    }
}
