<?php

namespace App\Form\Tricks;

use App\Entity\Categories;
use App\Entity\Tricks;
use App\Form\ImagesType;
use App\Form\VideoType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditTricksType extends AbstractType
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
                'name',
                TextType::class,
                [
                    'label' => 'Nom du tricks',
                    'attr'  => ['class' => 'form-control d-inline w-50'],
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'label' => 'Description du tricks',
                    'attr'  => ['class' => 'form-control'],
                ]
            )
            ->add(
                'category',
                EntityType::class,
                [
                    'class'        => Categories::class,
                    'choice_label' => 'name',
                    'label'        => "Catégorie",
                    'attr'         => ['class' => 'form-control Width25ToResponsive75'],
                ]
            )
            ->add(
                'images',
                CollectionType::class,
                [
                    'entry_type'   => ImagesType::class,
                    'mapped'       => false,
                    'required'     => false,
                    'allow_add'    => true,
                    'allow_delete' => true,
                ]
            )
            ->add(
                'videos',
                CollectionType::class,
                [
                    'entry_type'   => VideoType::class,
                    'mapped'       => false,
                    'required'     => false,
                    'allow_add'    => true,
                    'allow_delete' => true,
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => "Modifier",
                    'attr'  => ['class' => 'btn btn-primary m-3'],
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
                'data_class' => Tricks::class,
            ]
        );
    }


}
