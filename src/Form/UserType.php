<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, ['label' => 'Voornaam'])
            ->add('lastname', TextType::class, ['label' => 'Achternaam'])
            ->add('email' , TextType::class, ['label' => 'Email'])
            ->add('imgUrl', TextType::class, ['label' => 'Afbeelding URL'])
            ->add('submit', SubmitType::class, ['label' => 'Toevoegen'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
