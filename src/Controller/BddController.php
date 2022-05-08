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
}
