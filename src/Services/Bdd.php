<?php

namespace App\Services;

use App\Repository\TrtUserRepository;
use App\Repository\TrtAnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;

use App\Repository\TrtProfilcandidatRepository;
use App\Repository\TrtProfilrecruteurRepository;

class Bdd
{
    public $reposUser;
    public $reposProfilCdt;
    public $reposProfilRecr;
    public $entityManager;
    public $reposAnnonce;



    public function __construct(TrtUserRepository $reposUser_, TrtProfilcandidatRepository $reposProfilCdt_, TrtProfilrecruteurRepository $reposProfilRecr_, EntityManagerInterface $entityManager_, TrtAnnonceRepository $reposAnnonce_)
    {
        $this->reposUser = $reposUser_;
        $this->reposProfilCdt = $reposProfilCdt_;
        $this->reposProfilRecr = $reposProfilRecr_;
        $this->reposAnnonce = $reposAnnonce_;
        $this->entityManager = $entityManager_;
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
