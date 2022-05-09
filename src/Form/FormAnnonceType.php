<?php

namespace App\Form;

use App\Entity\TrtAnnonce;
use App\Repository\TrtContratRepository;
use Symfony\Component\Form\AbstractType;
use App\Repository\TrtEtatAnnonceRepository;
use App\Repository\TrtExperiencesRepository;
use App\Repository\TrtProfessionsRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class FormAnnonceType extends AbstractType
{
    public $repospro;
    public $reposExp;
    public $reposcrt;
    public $repoEtA;
    public function __construct(TrtProfessionsRepository $repopro_, TrtExperiencesRepository $repoExp_, TrtContratRepository $repoCrt_, TrtEtatAnnonceRepository $repoEtA_)
    {
        $this->repospro = $repopro_;
        $this->reposExp = $repoExp_;
        $this->reposcrt = $repoCrt_;
        $this->repoEtA = $repoEtA_;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $professions = $this->repospro->findAll();
        $experiences = $this->reposExp->findAll();
        $contrats = $this->reposcrt->findAll();
        $etats = $this->repoEtA->findAll();
        $choixprofessions['aucun choix'] = null;
        foreach ($professions as $pro) {
            $choixprofessions[$pro->getTitre()] = $pro;
        }
        $choixExperience['aucun choix'] = null;
        foreach ($experiences as $exp) {
            $choixExperience[$exp->getTitre()] = $exp;
        }
        $choixContrats['aucun choix'] = null;
        foreach ($contrats as $crt) {
            $choixContrats[$crt->getTitre()] = $crt;
        }
        $choixEtats['aucun choix'] = null;
        foreach ($etats as $et) {
            $choixEtats[$et->getTitre()] = $et;
        }
        $builder
            ->add('description',CKEditorType::class)
            ->add('horaire')
            ->add('salaireAnnuel')
            ->add('profession', ChoiceType::class, [
                'choices'  => $choixprofessions
            ])
            ->add('experience', ChoiceType::class, [
                'choices'  => $choixExperience
            ])
            ->add('contrat', ChoiceType::class, [
                'choices'  => $choixContrats
            ])

            ->add('etat', ChoiceType::class, [
                'choices'  => $choixEtats
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TrtAnnonce::class,
        ]);
    }
}
