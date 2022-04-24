<?php

namespace App\DataFixtures;

use App\Entity\TrtUser;
use App\Entity\TrtExperiences;
use App\Entity\TrtProfessions;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Repository\TrtExperiencesRepository;
use App\Repository\TrtProfessionsRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;
    private $reposProfessions;
    private $reposExperience;
    public function __construct(UserPasswordHasherInterface $passwordEncoder_, TrtProfessionsRepository $reposProfessions_, TrtExperiencesRepository $reposExperience_)
    {
        $this->passwordEncoder = $passwordEncoder_;
        $this->reposProfessions = $reposProfessions_;
        $this->reposExperience = $reposExperience_;
    }




    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        $user1 = new TrtUser();
        $user1->setEmail('jasminpascal2016@gmail.com');
        $user1->setRoles(['ROLE_ADMIN']);
        $user1->setProfil(0);
        $user1->setValider(1);
        $user1->setPassword($this->passwordEncoder->hashPassword($user1, 'AdminJasmin!2022'));
        $manager->persist($user1);

        $user2 = new TrtUser();
        $user2->setEmail('AdminitrateurTrt@laposte.net');
        $user2->setRoles(['ROLE_ADMIN']);
        $user2->setProfil(0);
        $user2->setValider(1);
        $user2->setPassword($this->passwordEncoder->hashPassword($user2, 'AdminTrt!2022'));
        $manager->persist($user2);
        $manager->flush();

        $manager->flush();
        // Professions
        $listeprofessions = array(
            "Barman/barmaid", "Cuisinier/Cuisinière", "chef de partie",
            "Un/une commis de cuisine", "Pâtissier/Pâtissière", "Sommelier/Sommelière",
            "Serveur/serveuse",
            "plongeur",
            "Gérant/Gérante",
            "Directeur/Directrice de restaurant",
            "Gouvernant/Gouvernante",
            "Femme/Valet de chambre",
            "Maître/Maîtresse d'hôtel",
            "un/une Réceptionniste",
            "Directeur/Directrice d'hotel",
            "directeur/Directrice financier"
        );
        $listecv = array(
            "barman", "cuisinier", "chefdepartie",
            "commisdecuisine", "patissier", "sommelier",
            "serveur", "plongeur", "gerant", "directeur",
            "gouvernant", "valetdechambre",  "maitredhotel", "receptionniste",
            "directeur", "directeurfinancier"
        );

        foreach ($listeprofessions as $profess) {
            $profession = new TrtProfessions();
            $profession->setTitre($profess);
            $manager->persist($profession);
            $arrayProf[] = $profession;
        }
        $manager->flush();
        // liste d 'experiences
        $listeExperiences = array("moins d'un ans d'expérience", "1 an  d'expérience", "2 ans d'expérience", "3 ans d'expériences", "4 ans d'expériences", "5 ans d'expériences", "entre 5 et 10 ans d'expériences", "entre 10 et 15 ans d'expériences", "entre 15 et 20 ans d'expériences", "Plus 20 ans d'expériences");
        foreach ($listeExperiences as $exp) {
            $experience = new TrtExperiences();
            $experience->setTitre($exp);
            $manager->persist($experience);
            $arrayExp[] = $experience;
        }
        $manager->flush();
    }
}
