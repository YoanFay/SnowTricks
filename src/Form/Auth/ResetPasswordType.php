<?php

namespace App\Form\Auth;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordType extends AbstractType
{


    /**
     * @param FormBuilderInterface $builder parameter
     * @param array                $options parameter
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add(
                'password', RepeatedType::class,
                ['type'            => PasswordType::class,
                 'invalid_message' => 'Les deux mots de passe ne correspondent pas.',
                 'options'         => ['attr' => ['class' => 'password-field']],
                 'required'        => true,
                 'first_options'   => ['label' => 'Mot de passe'],
                 'second_options'  => ['label' => 'Confirmer mot de passe'],
                ]
            )
            ->add('submit', SubmitType::class, ['label' => "Envoyer"]);

    }


    /**
     * @param OptionsResolver $resolver parameter
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
    }


}
