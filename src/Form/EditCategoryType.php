<?php

namespace App\Form;

use App\Entity\Catagories;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Categorie Naam',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Categorie Naam'
                ]
            ] )
            ->add('icon', TextType::class, [
                'label' => 'Icon',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'e.g. fa fa-home'
                ]
            ])
            ->add('submit', SubmitType::class, ['label' => 'Update'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Catagories::class,
        ]);
    }
}
