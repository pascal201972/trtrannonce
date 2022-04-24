<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class CandidatController extends BddController
{
    public function index(SluggerInterface $slugger, Request $request): Response
    {
        $user = $this->getUser();
        if ($user->getTrtProfilcandidat())
            $profil = $this->reposProfilCdt->findOneByUser($user);
        $complet = $this->isProfilComplet($profil, 'ROLE_CANDIDAT');

        $formprofil = $this->createForm(FormProfilCandidatType::class, $profil);
        $formprofil->handleRequest($request);
        $formCv = $this->createForm(FormCvType::class);
        $formCv->handleRequest($request);

        if ($formCv->isSubmitted() && $formCv->isValid()) {
            $cv = $formCv->get('cvpdf')->getData();

            if ($cv) {

                $originalFilename = pathinfo($cv->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $cv->guessExtension();

                try {
                    if ($profil->getCv() != "") {
                        unlink($this->getParameter('repertoire_cv') . '/' .  $profil->getCv());
                    }
                    $cv->move(
                        $this->getParameter('repertoire_cv'),
                        $newFilename
                    );
                    $profil->setCv($newFilename);
                    $this->entityManager->persist($profil);
                    $this->entityManager->flush();
                    $this->setProfilComplet($user, $profil, 'ROLE_CANDIDAT');
                    return $this->redirectToRoute('app_candidat');
                } catch (FileException $e) {

                    // $product->setBrochureFilename($newFilename);
                }
            }
        }
        if ($formprofil->isSubmitted() && $formprofil->isValid()) {

            $this->entityManager->persist($profil);
            $this->entityManager->flush();
            $this->setProfilComplet($user, $profil, 'ROLE_CANDIDAT');
            return $this->redirectToRoute('app_candidat');
        }

        return $this->render('candidat/candidat.html.twig', [
            'page' => 'administration',
            'onglet' => 'profil',
            'profil' => $profil,
            'formProfil' => $formprofil->createView(),
            'formcv' => $formCv->createView(),
            'complet' => $complet

        ]);
    }

}
