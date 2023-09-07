<?php

namespace App\Form;

use App\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoType extends AbstractType
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
                'link',
                UrlType::class,
                [
                    'label' => 'Lien',
                    'attr'  =>
                        [
                            'class'       => 'form-control mb-3 mt-1',
                            'placeholder' => 'https://www.youtube.com/embed/.....'
                        ],
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
                'data_class' => Video::class,
            ]
        );
    }


}
