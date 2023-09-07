<?php

namespace App\Form;

use App\Entity\Images;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImagesType extends AbstractType
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
                'images',
                FileType::class,
                ['label'    => false,
                 'required' => false,
                 'mapped'   => false,
                 'attr'     => ['class' => 'form-control mb-2 mt-3 checkboxMain'],
                ]
            )
            ->add(
                'main',
                CheckboxType::class,
                ['label'    => "Mettre cette image en tant qu'image principale ?",
                 'required' => false,
                 'attr'     => ['class' => 'mx-2 mb-3 checkboxMain'],
                ]
            );
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
                'data_class' => Images::class,
            ]
        );
    }


}
