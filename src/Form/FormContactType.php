<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class FormContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'required' => true,
                'label' => 'votre nom',
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'votre email',
            ])
            ->add('sujet', TextType::class, [
                'label' => 'le sujet',
            ])
            ->add('message', TextareaType::class, [
                'attr' => ['rows' => 5],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez completer le message'
                    ]),
                    new Length([
                        'min' => 20,
                        'minMessage' => 'Le ntext doit contenir au minimum {{ limit }} caractères'
                    ]),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
