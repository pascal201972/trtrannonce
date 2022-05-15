<?php

namespace App\Controller;

use App\Form\MdpFormType;
use App\Services\EnvoieEmail;
use App\Form\ResetPassEmailType;
use App\Controller\BddController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\TrtProfilcandidatRepository;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TrtProfilrecruteurRepository;

class ConsultantController extends BddController
{
    /**
     * 
     * @Route("/consultant", name="app_consultant")
     *
     * IsGranted("ROLE_CONSULTANT")
     * @return Response
     */
    public function index(Request $request): Response
    {
        $route = "app_consultant";
        $user = $this->getUser();
        $formemail = $this->createForm(ResetPassEmailType::class);
        $formMdp = $this->createForm(MdpFormType::class);
        $this->formprofil($route, $user, $request, $formemail, $formMdp);
        return $this->render(
            'consultant/consultant.html.twig',
            [
                'onglet' => 'profil',
                'formemail' => $formemail->createView(),
                'formMdp' => $formMdp->createView()
            ]
        );
    }


    /**
     * 
     * @Route("/consultant/validation/{id}", name="app_consultant_validation")
     * IsGranted("ROLE_CONSULTANT")
     * @return Response
     */
    public function Validation($id)
    {

        $user = $this->reposUser->findOneBy(['id' => $id]);

        $profil = $this->btn_validation($user, 1);

        return $this->redirectToRoute('app_consultant_' . $profil, [
            'onglet' => $profil,
            'suppression' => false,
            'fiche' => false,
            'valider' => 'nonvalider'
        ]);
    }

    /**
     * 
     * @Route("/consultant/desactivation/{id}", name="app_consultant_desactivation")
     * IsGranted("ROLE_CONSULTANT")
     * @return Response
     */
    public function desactivation($id)
    {
        $user = $this->reposUser->findOneBy(['id' => $id]);
        $profil = $this->getProfilUser($user);
        if ($profil == 'candidat') {
            $candidature = $this->getListeCandidature($user);
            if (!$candidature) $profil = $this->btn_validation($user, 0);
        }
        if ($profil == 'recruteur') {
            $profiluser = $user->getTrtProfilrecruteur();
            $annonces = $this->reposAnnonce->findBy(['recruteur' => $user->getTrtProfilrecruteur()->getId()]);
            if (!$annonces) $profil = $this->btn_validation($user, 0);
        }
        return $this->redirectToRoute('app_consultant_' . $profil, [
            'onglet' => $profil,
            'suppression' => false,
            'fiche' => false,
            'valider' => 'nonvalider'
        ]);
    }







    public function Supprimerprofil($id)
    {
        $user = $this->reposUser->findOneBy(['id' => $id]);
        $profiluser = $this->getProfilByUser($user);
        $profilname = $this->getProfilUser($user);
        if ($user) {
            if (!$user->getValider()) {
                if ($profilname == 'candidat') {
                    $profiluser = $user->getTrtProfilcandidat();
                }

                if ($profilname == 'recruteur') {
                    $profiluser = $user->getTrtProfilrecruteur();
                }
            }
        }
        return $this->render('consultant/consultant_recruteurs/consultant_recruteurs.html.twig', [
            'onglet' => $profilname,
            'profil' => $profiluser,
            'suppression' => true,
            'fiche' => false,
            'valider' => 'nonvalider'
        ]);
    }
}
