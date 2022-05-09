<?php

namespace App\Controller;

use App\Form\FormContactType;
use App\Services\EnvoieEmail;
use App\Controller\BddController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends BddController
{

    /** 
     *  @Route("/contact", name="app_contact")
     */
    public function index(EnvoieEmail $envoieEmail, Request $request): Response
    {
        $profession = $this->reposProfession->findAll();
        $form = $this->createForm(FormContactType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $to = 'trtrecrutement2022@gmail.com';
            $from = $form->get('email')->getData();
            $message = $form->get('message')->getData();
            $subject = $form->get('sujet')->getData();
            $envoieEmail->SendEmailContact($from, $to, $subject, $message);
            $this->addFlash('successcontact', "votre message  à été envoyé.");
            return $this->redirectToRoute('app_contact');
        }






        return $this->render('contact/contact.html.twig', [
            'profession' => $profession,
            'formcontact' => $form->createView()
        ]);
    }
}
