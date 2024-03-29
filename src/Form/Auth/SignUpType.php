<?php

namespace App\Form\Auth;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SignUpType extends AbstractType
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
            ->add('login', TextType::class, ['label' => 'Identifiant'])
            ->add('mail', TextType::class, ['label' => 'Email'])
            ->add(
                'password',
                RepeatedType::class,
                ['type'            => PasswordType::class,
                 'invalid_message' => 'Les deux mots de passe ne correspondent pas.',
                 'options'         => ['attr' => ['class' => 'password-field']],
                 'required'        => true,
                 'first_options'   => ['label' => 'Mot de passe'],
                 'second_options'  => ['label' => 'Confirmer mot de passe'],
                ]
            )
            ->add('submit', SubmitType::class, ['label' => "Inscription"]);

    }


    /**
     * @param OptionsResolver $resolver parameter
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {

        $resolver->setDefaults(
            [
            'data_class' => User::class,
        ]
        );
    }


}
