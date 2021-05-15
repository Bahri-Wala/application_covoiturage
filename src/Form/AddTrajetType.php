<?php

namespace App\Form;

use App\Entity\Trajet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddTrajetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Depart')
            ->add('Arrivee')
            ->add('Date', DateTimeType::class, ['placeholder' => ['year' => 'Year', 'month' => 'Month', 'day' => 'Day', 'hour' => 'Hour', 'minute' => 'Minute', 'second' => 'Second']])
            ->add('prix', MoneyType::class, ['currency' => 'TND', 'help' => 'Montant en Dinars'])
            ->add('places', NumberType::class, ['help' => 'Un faux nombre va étre signalé'])
            ->add('user')
            ->add('Postuler', SubmitType::class)
            ->add('reinitialiser', ResetType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trajet::class,
        ]);
    }
}
