<?php

namespace App\Controller;

use App\Entity\TrtUser;
use App\Form\MdpFormType;
use App\Services\EnvoieEmail;
use App\Form\ResetPassEmailType;
use App\Form\RegistrationFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminController extends BddController
{
    /**
     *@Route("/admin/", name="app_admin")
     * IsGranted("ROLE_ADMIN")
     */


    public function index(Request $request): response
    {
        $route = "app_admin";
        $user = $this->getUser();
        $formemail = $this->createForm(ResetPassEmailType::class);
        $formMdp = $this->createForm(MdpFormType::class);
        $this->formprofil($route, $user, $request, $formemail, $formMdp);

        return $this->render('admin/admin.html.twig', [
            'page' => 'administration',
            'onglet' => 'profil',
            'formemail' => $formemail->createView(),
            'formMdp' => $formMdp->createView()
        ]);
    }
    /**
     * 
     *
     * 
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @param EnvoieEmail $envoieEmail
     * @return Response
     * 
     * @Route("/admin/consultant", name="app_admin_consultant")
     * IsGranted("ROLE_ADMIN")
     */

    public function listeConsultant(UserPasswordHasherInterface $userPasswordHasher, EnvoieEmail $envoieEmail, Request $request): Response
    {
        $user = new TrtUser();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles('ROLE_CONSULTANT');

            $user->setValider(1);
            $user->setProfil(0);

            $this->entityManager->persist($user);

            $this->entityManager->flush();

            $subject = "Vous ête consultant";
            $template = 'templateEmail/email_consultant.html.twig';
            $context = [""];
            $envoieEmail->SendEmailSimple($user->getEmail(), $subject, $template);
            return $this->redirectToRoute('app_admin_consultant');
        }
        $listeConsultant = $this->getUserRole('ROLE_CONSULTANT');


        return $this->render('admin/admin.html.twig', [
            'page' => 'administration',
            'formconsultant' => $form->createView(),
            'fiche' => false,
            'liste' => $listeConsultant,
            'onglet' => 'consultant'
        ]);
    }
    /**    
     *   @Route("/admin/consultant/supprimer/{id}", name="app_admin_consultant_supprimer")
     * @Route("/admin/consultant/supprimer/{confirmer}/{id}", name="app_admin_consultant_supprimer_confirmer")
     * IsGranted("ROLE_ADMIN")
     */
    public function supprimerConsultant($id, $confirmer = null)
    {
        $listeConsultant = $this->getUserRole('ROLE_CONSULTANT');
        $fiche = $this->reposUser->findOneByRoleAndId($id, 'ROLE_CONSULTANT');
        if ($confirmer == 'confirmer') {
            $this->entityManager->remove($fiche);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_admin_consultant');
        }
        return $this->render('admin/admin.html.twig', [
            'page' => 'administration',
            'ficheconsultant' => $fiche,
            'fiche' => true,
            'liste' => $listeConsultant,
            'onglet' => 'consultant'
        ]);
    }


}
