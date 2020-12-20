<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangeInformationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'disabled' => true
            ])
            ->add('lastname', TextType::class, [
                'disabled' => true
            ])
            ->add('email', EmailType::class, [
                'disabled' => true
            ])
            ->add('old_password', PasswordType::class, [
                'label' => 'Ancien mot de passe',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Veuillez indiquer votre ancien mot de passe'
                ]
            ])
            ->add('new_password', RepeatedType::class, [
                'type'=> PasswordType::class,
                'mapped' => false,
                'invalid_message'=> 'Vos mots de passe ne sont pas identique',
                'label'=> 'Votre mot de passe',
                'required'=>true,
                'first_options'=>[
                    'label'=>'Mot de passe',
                    'attr' => [
                        'placeholder' => 'Veuillez saisir votre mot de passe'
                    ]
                ],
                'second_options'=>[
                    'label'=>'Confirmer votre de passe',
                    'attr' => [
                        'placeholder' => 'Veuillez confirmer votre mot de passe'
                    ]
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Mettre à jour'
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
