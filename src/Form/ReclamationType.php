<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, ['label' => "Titre"])
            ->add('description', TextareaType::class, ['label' => "Description"])
            ->add('priorite', ChoiceType::class, [
                'label' => 'Priorité',
                'choices' => [
                    'Urgent' => 'Urgent',
                    'Moyen' => 'Moyen',
                    'Bas' => 'Bas',
                ],
                'expanded' => false, // Liste déroulante
                'multiple' => false, // Un seul choix possible
            ])
            ->add('categorie', ChoiceType::class, [
                'choices' => [
                    'Problèmes de paiement' => 'Problèmes de paiement',
                    'Conduite inappropriée d\'un conducteur' => 'Conduite inappropriée d\'un conducteur',
                    'Passager non ponctuel' => 'Passager non ponctuel',
                    'Autres' => 'Autres',
                ],
                'label' => 'Catégorie',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
