<?php

namespace App\Controller;

use App\Repository\TrtUserRepository;
use App\Repository\TrtAnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TrtCandidatureRepository;
use App\Repository\TrtProfessionsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\TrtProfilcandidatRepository;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TrtProfilrecruteurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class BddController extends AbstractController
{
    public $reposUser;
    public $reposProfilCdt;
    public $reposProfilRecruteur;
    public $entityManager;
    public $reposAnnonce;
    public $reposCandidature;
    public $passwordEncoder;
    public $reposProfession;





    public function __construct(
        TrtUserRepository $reposUser_,
        TrtProfilcandidatRepository $reposProfilCdt_,
        TrtProfilrecruteurRepository $reposProfilRecr_,
        EntityManagerInterface $entityManager_,
        TrtAnnonceRepository $reposAnnonce_,
        TrtCandidatureRepository $repoCandidature_,
        UserPasswordHasherInterface $passwordEncoder_,
        TrtProfessionsRepository $reposprofession_
    ) {
        $this->reposUser = $reposUser_;
        $this->reposProfilCdt = $reposProfilCdt_;
        $this->reposProfilRecruteur = $reposProfilRecr_;
        $this->reposAnnonce = $reposAnnonce_;
        $this->entityManager = $entityManager_;
        $this->reposCandidature = $repoCandidature_;
        $this->passwordEncoder = $passwordEncoder_;
        $this->reposProfession = $reposprofession_;
    }
    public function formprofil($route, $user, Request $request,  $formemail, $formMdp)
    {

        $formemail->handleRequest($request);

        if ($formemail->isSubmitted() && $formemail->isValid()) {
            $user->setEmail($formemail->get('email')->getData());
            $this->entityManager->persist($user);

            $this->entityManager->flush();
            $this->redirectToRoute($route);
        }

        $formMdp->handleRequest($request);
        if ($formMdp->isSubmitted() && $formMdp->isValid()) {
            $mdp = $this->passwordEncoder->hashPassword($user, $formMdp->get('plainPassword')->getData());
            $user->setPassword($mdp);
            $this->entityManager->persist($user);

            $this->entityManager->flush();
            $this->redirectToRoute($route);
        }
    }



    public function getUserRole($role)
    {
        $userRoles = array();
        $listeuser = $this->reposUser->findAll();
        foreach ($listeuser as $user) {
            $roles = $user->getRoles();

            if ($roles[0] == $role) {
                $userRoles[] = $user;
            }
        }
        return $userRoles;
    }

    public function getUserByRoleAndEtat($role, $etat)
    {
        $userRoles = array();
        $listeuser = $this->reposUser->findAll();
        foreach ($listeuser as $user) {
            $roles = $user->getRoles();

            if ($roles[0] == $role) {
                $userRoles[] = $user;
            }
        }
        return $userRoles;
    }
    public function isProfilComplet($user)
    {
        $roles = $user->getRoles();
        $role = $roles[0];

        if ($role == 'ROLE_CANDIDAT') {
            $profil = $user->getTrtProfilcandidat();
            if ($profil->getNom() != "" && $profil->getPrenom() != "" && $profil->getCv() != "" && $profil->getProfession()->getId() != 0 && $profil->getExperience()->getId() != 0)
                $bool = true;
            else $bool = false;
        }
        if ($role == 'ROLE_RECRUTEUR') {
            $profil = $user->getTrtProfilrecruteur();
            if ($profil->getNom() != "" && $profil->getAdresse() != "" && $profil->getCodePostal() != "" && $profil->getVille() != "" && $profil->getEtablissement() != "") $bool = true;
            else $bool = false;
        }
        return $bool;
    }

    public function setProfilComplet($profil, $user)
    {
        $profilValider = $this->isProfilComplet($user);
        if ($profilValider) $user->setProfil(true);
        else $user->setProfil(false);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return  $profilValider;
    }

    public function isAnnonceComplet($annonce)
    {
        if ($annonce->getDescription() != "" && $annonce->getHoraire() != "" && $annonce->getSalaireAnnuel() != "" && $annonce->getProfession()->getId() != 0 && $annonce->getExperience()->getId() != 0 && $annonce->getContrat()->getId() && $annonce->getRecruteur()->getId() != 0)
            return true;
        else return false;
    }

    public function setAnonceComplet($annonce)
    {
        if ($this->isAnnonceComplet($annonce)) $annonce->setComplet(true);
        else $annonce->setComplet(false);
        $this->entityManager->persist($annonce);
        $this->entityManager->flush();
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

    public function getProfilByUser($user)
    {

        if ($user) {
            $profilname = $this->getProfilUser($user);
            if (!$user->getValider()) {
                if ($profilname == 'candidat') {
                    return $profiluser = $user->getTrtProfilcandidat();
                }

                if ($profilname == 'recruteur') {
                    return  $profiluser = $user->getTrtProfilrecruteur();
                }
            }
        }
    }


    public  function getListe($id = null, $profilrole, $onglet, $valider)
    {
        $valid = $this->getParametreValid($valider);
        $userprofil = null;
        $fiche = false;
        $profilname = "";
        if ($id != null) {
            $userprofil = $this->reposUser->findOneBy(["id" => $id]);

            $profilname = $this->getProfilUser($userprofil);
            switch ($profilname) {
                case 'candidat':
                    $ficheprofil = $this->reposProfilCdt->findOneBy(['idUser' => $userprofil]);
                    break;
                case 'recruteur':
                    $ficheprofil = $this->reposProfilRecruteur->findOneBy(['idUser' => $userprofil]);
                    break;
            }
            $fiche = true;
        } else  $ficheprofil = array();


        $listes = $this->reposUser->findUserByRoleAndEtat($profilrole, $valid);

        $candidature = array();
        if ($valid == true) {
            foreach ($listes as $u) {
                $profilname = $this->getProfilUser($u);
                switch ($profilname) {
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
        }


        return $parametres = [
            'page' => 'administration',
            'onglet' => $onglet,
            'listes' => $listes,
            'userprofil' => $ficheprofil,
            'profil' => $profilname,
            'fiche' => $fiche,
            'valider' => $valider,
            'candidature' => $candidature,
            'suppression' => false

        ];
    }


    public function btn_validation($user, $etat)
    {


        $profil = $this->getProfilUser($user);

        if ($this->setProfilComplet($profil, $user)) {
            $user->setValider($etat);
        } else $user->setValider(false);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $profil;
    }


    public function getannonce($fiche, $valider)
    {
        $valid = $this->getParametreValid($valider);
        $listeAnn = $this->reposAnnonce->findBy(['valider' => $valid]);
        $listeAnnonces = array();
        $liste = array();
        $listecandidat = array();

        if ($valid == true) {
            foreach ($listeAnn as $annonce) {
                $liste['annonce'] = $annonce;
                $listecandidat[$annonce->getId()] = array();
                $candidat = $this->reposCandidature->findProfilcandidatureByAnnonce($annonce->getId());

                $listecandidat[$annonce->getId()] = $candidat;

                $listeAnnonces[] = $liste;
            }
        } else {
            $listeAnnonces =  $listeAnn;
        }

        $parametre = [
            'onglet' => 'annonce',
            'fiche' => $fiche,
            'listeannonces' => $listeAnnonces,
            'valid' => $valid,
            'valider' => $valider,
            'listeCandidat' => $listecandidat,
            'suppression' => false,
            'ficheliste' => false
        ];
        return $parametre;
    }

    public function getParametreValid($valid)
    {


        if ($valid == 'valider') return true;
        else return false;
    }



    public function Confirmersupprimerprofil($id)
    {
        $user = $this->reposUser->findOneBy(['id' => $id]);
        $profilname = $this->getProfilUser($user);
        if ($user) {
            if (!$user->getValider()) {
                if ($profilname == 'candidat') {
                    $profiluser = $user->getTrtProfilcandidat();
                    $candidature = $this->reposCandidature->findBy(['profil' => $profiluser->getId()]);
                    foreach ($candidature as $cdt) {
                        $this->entityManager->remove($cdt);
                    }
                    $this->entityManager->flush();
                }

                if ($profilname == 'recruteur') {
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
    }
}
