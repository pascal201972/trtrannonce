<?php

namespace App\Form;

use App\Entity\TrtProfilrecruteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormProfilRecruteurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, ['empty_data' => ''])
            ->add('etablissement', ChoiceType::class, [
                'choices'  =>  ['Aucun' => '', 'Hotel' => 'Hotel', 'Restaurant' =>  'Restaurant'],
                'empty_data' => ''
            ])
            ->add('adresse', null, ['empty_data' => ''])
            ->add('codePostal', null, ['empty_data' => ''])
            ->add('ville', null, ['empty_data' => '']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TrtProfilrecruteur::class,
        ]);
    }
}
