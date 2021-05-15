<?php

namespace App\Form;

use App\Entity\Voiture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddVoitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('marque')
            ->add('couleur', ChoiceType::class, ['help' => 'Choisissez le couleur le plus proche de votre voiture..', 'choices' => ['Rouge' => 'Rouge', 'Noire' => 'Noire', 'Blanche' => 'Blanche', 'Jaune' => 'Jaune', 'Bleue' => 'Bleue', 'Verte' => 'Verte']])
            ->add('Climatiseur', ChoiceType::class, ['choices' => ['Fonctionne' => 'Fonctionne', 'ne fonctionne pas' => 'ne fonctionne pas']])
            ->add('Tabac', ChoiceType::class, ['choices' => ['Autorisé' => 'Autorisé', "N'est pas autorisé" => 'N\'est pas autorisé']])
            ->add('Envoyer', SubmitType::class)
            ->add('Reset', ResetType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }
}
