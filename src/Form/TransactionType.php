<?php

namespace App\Form;

use App\Entity\Catagories;
use App\Entity\Transactions;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount')
            ->add('transaction_date', DateTimeType::class, [
                'label' => 'Datum en tijd',
                'help' => 'Kies de datum en tijd van de transactie',
                'widget' => 'single_text',
                'html5' => true, // render as type="datetime-local"
                'attr' => [
                    'step' => 60, // allow minute precision; set to 1 for seconds if needed
                ],
            ])
            ->add('description')
            ->add('category', EntityType::class, [
                'class' => Catagories::class,
                'choice_label' => 'name',
            ])

            ->add('submit', SubmitType::class, ['label' => 'Verzenden'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transactions::class,
        ]);
    }
}
