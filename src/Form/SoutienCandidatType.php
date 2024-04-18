<?php

namespace App\Form;

use App\Entity\Candidat;
use App\Entity\Petition;
use App\Entity\SoutienCandidat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SoutienCandidatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('petition', EntityType::class, [
                'class' => Petition::class,
                'attr' => [
                    'class' => 'form-control',
                    'required' => 'required',
                ],
                'required' => false
            ])

            ->add('candidat', EntityType::class, [
                'class' => Candidat::class,
                'attr' => [
                    'class' => 'form-control',
                    'required' => 'required',
                ],
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SoutienCandidat::class,
        ]);
    }
}
