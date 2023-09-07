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

class AddTricksType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('name', TextType::class, ['label' => 'Nom du tricks'])
            ->add('description', TextareaType::class, ['label' => 'Description du tricks'])
            ->add('category', EntityType::class,
                ['class'        => Categories::class,
                 'choice_label' => 'name',
                 'label'        => "Catégorie",
                ]
            )
            ->add('images', CollectionType::class,
                ['entry_type'   => ImagesType::class,
                 'mapped'       => false,
                 'required'     => false,
                 'allow_add'    => true,
                 'allow_delete' => true,
                ]
            )
            ->add('videos', CollectionType::class,
                ['entry_type'   => VideoType::class,
                 'mapped'       => false,
                 'required'     => false,
                 'allow_add'    => true,
                 'allow_delete' => true,
                ]
            )
            ->add('submit', SubmitType::class, ['label' => "Envoyer"]);
    }


    /**
     * @param OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {

        $resolver->setDefaults([
            'data_class' => Tricks::class,
        ]);
    }


}
