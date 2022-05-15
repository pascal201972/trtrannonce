<?php

namespace App\Controller;

use App\Entity\TrtUser;
use App\Services\EnvoieEmail;
use App\Entity\TrtInitpassword;
use App\Form\ResetPassEmailType;
use App\Form\FormInitpasswordType;
use App\Repository\TrtUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class ResetPasswordController extends AbstractController
{
    /** 
     * @Route("/reset/password", name= "app_reset_email")
     * 
     */

    public function getEmail(Request $request, TrtUserRepository $reposUser, TokenGeneratorInterface $tokenGenerator, EnvoieEmail $envoieEmail, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ResetPassEmailType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $user = $reposUser->findOneBy(['email' => $email]);
            if ($user == null) {
                $this->addFlash('ErreurEmail', 'Cette adresse e-mail est inconnue');

                // On retourne sur la page de connexion
                return $this->redirectToRoute('app_reset_email');
            } else {


                $token = $tokenGenerator->generateToken();
                try {

                    $time = time() + 3600;

                    $user->setInitMotdepasse($token);
                    $user->setExpire($time);

                    $entityManager->persist($user);
                    $entityManager->flush();
                } catch (\Exception $e) {
                    $this->addFlash('ErreurEmail', "une erreur s'est produite: recommencer");
                    return $this->redirectToRoute('app_reset_email');
                }



                $subject = "Réinitialisation de votre mot de passe";
                $template = 'templateEmail/email_init_password.html.twig';
                $context = ['token' => $token];
                $envoieEmail->SendEmail($email, $subject, $template, $context);
                $this->addFlash('successEmail', "Un email vient de vous être envoyé.");
                return $this->redirectToRoute('app_reset_email');
            }
        }
        return $this->render('reset_password/email.html.twig', ['EmailForm' => $form->createView()]);
    }


    /** 
     * @Route("/reset/new/password/{token}", name= "app_new_password")
     * 
     */
    public function nouveauPassword($token, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(FormInitpasswordType::class);

        $userInit = $this->getDoctrine()->getRepository(TrtUser::class)->findOneBy(['initMotdepasse' => $token]);

        if (!$userInit  || $userInit->getInitMotdepasse() != $token || time() > $userInit->getExpire()) {

            $this->addFlash('messageErreur', "une erreur s'est produite, le lien n'est plus valable recommencer");
            return $this->redirectToRoute('app_erreurs');
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $userInit->setPassword(
                $userPasswordHasher->hashPassword(
                    $userInit,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($userInit);
            $entityManager->flush();
            $this->addFlash('successpasswod', "votre mot de passe a été modifié");

            return $this->redirectToRoute('app_login');
        }
        return $this->render('reset_password/new_password.html.twig', ['formInitpassword' => $form->createView()]);
    }
}
