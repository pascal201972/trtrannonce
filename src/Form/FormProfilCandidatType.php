<?php

namespace App\Form;

use App\Entity\TrtProfilcandidat;
use App\Repository\TrtExperiencesRepository;
use Symfony\Component\Form\AbstractType;
use App\Repository\TrtProfessionsRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class FormProfilCandidatType extends AbstractType
{
    private $repospro;
    private $reposExp;
    public function __construct(TrtProfessionsRepository $repopro_, TrtExperiencesRepository $repoExp_)
    {
        $this->repospro = $repopro_;
        $this->reposExp = $repoExp_;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $professions = $this->repospro->findAll();
        $experiences = $this->reposExp->findAll();
        $choixprofessions['aucun choix'] = null;
        foreach ($professions as $pro) {
            $choixprofessions[$pro->getTitre()] = $pro;
        }
        $choixExperience['aucun choix'] = null;
        foreach ($experiences as $exp) {
            $choixExperience[$exp->getTitre()] = $exp;
        }
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('profession', ChoiceType::class, [
                'choices'  => $choixprofessions
            ])
            ->add('experience', ChoiceType::class, [
                'choices'  => $choixExperience
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TrtProfilcandidat::class,
        ]);
    }
}
