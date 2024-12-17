<?php

namespace App\Form;

use App\Entity\Reclamation;
use App\Entity\Reponse;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Le texte de la réponse
            ->add('texte', TextareaType::class, [
                'label' => 'Texte de la réponse',
                'attr' => ['class' => 'form-control', 'rows' => 5, 'placeholder' => 'Entrez votre réponse...']
            ])
            // La date de réponse (par défaut, on la met à la date actuelle)
            ->add('dateReponse', DateTimeType::class, [
                'label' => 'Date de la réponse',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'data' => new \DateTime() // date actuelle par défaut
            ])
            // Le statut "lue"
            ->add('lue', CheckboxType::class, [
                'label' => 'Réponse lue',
                'required' => false,
                'attr' => ['class' => 'form-check-input']
            ])
            // Association à une réclamation (sélectionner une réclamation existante)
            ->add('reclamation', null, [
                'label' => 'Réclamation associée',
                'class' => Reclamation::class,
                'choice_label' => 'titre', // Choisir quel champ afficher pour la réclamation
                'attr' => ['class' => 'form-control']
            ])
            // Bouton de soumission
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer la réponse',
                'attr' => ['class' => 'btn btn-primary']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reponse::class,
        ]);
    }
}
