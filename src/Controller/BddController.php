<?php

namespace App\Controller;

use App\Repository\TrtUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TrtProfilrecruteurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BddController extends AbstractController
{
    public $reposUser;
    public $reposProfilCdt;
    public $reposProfilRecruteur;
    public $entityManager;
    public $reposAnnonce;
    public $reposCandidature;




    public function __construct(
        TrtUserRepository $reposUser_,
        //   TrtProfilcandidatRepository $reposProfilCdt_,
        TrtProfilrecruteurRepository $reposProfilRecr_,
        EntityManagerInterface $entityManager_,
       // TrtAnnonceRepository $reposAnnonce_,
       // TrtCandidatureRepository $repoCandidature_,
    ) {
        $this->reposUser = $reposUser_;
        //   $this->reposProfilCdt = $reposProfilCdt_;
        $this->reposProfilRecruteur = $reposProfilRecr_;
       // $this->reposAnnonce = $reposAnnonce_;
        $this->entityManager = $entityManager_;
      //  $this->reposCandidature = $repoCandidature_;
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


    public function isProfilComplet($profil, $role)
    {

        if ($role == 'ROLE_CANDIDAT') {

            if ($profil->getNom() != "" && $profil->getPrenom() != "" && $profil->getCv() != "" && $profil->getProfession()->getId() != 0 && $profil->getExperience()->getId() != 0)
                return true;
            else return false;
        }
        if ($role == 'ROLE_RECRUTEUR') {

            if ($profil->getNom() != "" && $profil->getAdresse() != "" && $profil->getCodePostal() != "" && $profil->getVille() != "" && $profil->getEtablissement() != "") return true;
            else return false;
        }
    }

    public function setProfilComplet($user, $profil, $role)
    {
        if ($this->isProfilComplet($profil, $role)) $user->setProfil(true);
        else $user->setProfil(false);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
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
