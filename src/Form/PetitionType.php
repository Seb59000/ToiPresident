<?php

namespace App\Form;

use App\Entity\Petition;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PetitionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'required' => 'required',
                ],
                'required' => false
            ])
            ->add('contenu', CKEditorType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'required' => 'required',
                ],
                'required' => false
            ])

            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'attr' => [
                    'class' => 'form-control',
                    'required' => 'required',
                ],
                'required' => false
            ])
            /*
            ->add(
                'categorie',
                ChoiceType::class,
                [
                    'choices'  => [
                        'Droits des femmes' => 'Droits des femmes',
                        'Droits de l homme' => 'Droits de l homme',
                        'Environnement' => 'Environnement',
                        'Racisme' => 'Racisme',
                        'Politique' => 'Politique',
                        'Handicap' => 'Handicap',
                        'Animaux' => 'Animaux',
                        'Attentats' => 'Attentats',
                        'Culture' => 'Culture',
                        'Consommation' => 'Consommation',
                        'Éducation' => 'Éducation',
                        'Immigration' => 'Immigration',
                        'Justice' => 'Justice',
                        'Économique' => 'Économique',
                        'Liberté d expression' => 'Liberté d expression',
                        'Médias' => 'Médias',
                        'Patrimoine' => 'Patrimoine',
                        'Pollution' => 'Pollution',
                        'Réfugiés' => 'Réfugiés',
                        'Santé' => 'Santé',
                        'Sexisme' => 'Sexisme',
                        'Transport' => 'Transport',
                        'Terrorisme' => 'Terrorisme',
                    ],
                    'attr' => ['class' => 'form-control',],
                ]
            )
            */;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Petition::class,
        ]);
    }
}
