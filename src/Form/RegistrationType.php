<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('sexe', ChoiceType::class, ['choices' => ['Homme' => 'Homme', 'Femme' => 'Femme']])
            ->add('Image', FileType::class, array('mapped' => false, 'required' => false, 'empty_data' => 'empty'))
            ->add('birthday')
            ->add('email')
            ->add('password', PasswordType::class)
            ->add('confirm_password', PasswordType::class)
            ->add('numero', NumberType::class)
            ->add('profession')
            ->add('Envoyer!', SubmitType::class)
            ->add('reinitialiser', ResetType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
