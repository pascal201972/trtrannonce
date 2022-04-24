<?php

namespace App\Controller;

use App\Entity\TrtUser;
use App\Entity\TrtProfilcandidat;
use App\Entity\TrtProfilrecruteur;
use App\Form\RegistrationFormType;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register/", name= "app_registers")
     * @Route("/register/{type}", name= "app_register")
     */
    public function register($type = Null, Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new TrtUser();
        $form = $this->createForm(RegistrationFormType::class, $user);
        if ($type == null) return $this->redirectToRoute('app_register', ['type' => 'candidat']);

        if ($type != 'candidat')
            if ($type != 'recruteur') return $this->redirectToRoute('app_register', ['type' => 'candidat']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            if ($type == 'recruteur') {
                $user->setRoles(['ROLE_RECRUTEUR']);
                $profil = new TrtProfilrecruteur();
            } else {
                $user->setRoles(['ROLE_CANDIDAT']);
                $profil = new TrtProfilcandidat();
            }

            $user->setValider(0);
            $user->setProfil(0);
            $entityManager->persist($user);

            $profil->setIdUser($user);
            $entityManager->persist($profil);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'page' => 'register',
            'type' => $type
        ]);
    }
}
