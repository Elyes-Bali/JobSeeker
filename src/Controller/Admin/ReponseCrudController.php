<?php

namespace App\Controller\Admin;

use App\Entity\Reponse;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ReponseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reponse::class;
    }

    // Configuration des champs qui seront affichés dans EasyAdmin
    // Configuration des champs qui seront affichés dans EasyAdmin
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->hideOnForm(),  // Masquer l'ID dans le formulaire
            TextEditorField::new('texte', 'Texte'),  // Champ de texte pour le contenu de la réponse
            DateTimeField::new('dateReponse', 'Date de Réponse')
                ->setFormat('short'),  // Affichage de la date au format court
            BooleanField::new('lue', 'Lue'),  // Champ booléen pour savoir si la réponse est lue
            AssociationField::new('reclamation', 'Réclamation')  // Association avec l'entité Reclamation
            ->setFormTypeOptions(['choice_label' => 'titre']),  // Utilisation du titre de la réclamation dans la sélection
        ];
    }

    // Configuration des actions disponibles (édition, suppression, etc.)
    public function configureActions(Actions $actions): Actions
    {
        // Personnalisation de l'action "saveAndReturn" dans la page d'édition
        $actions = $actions
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_RETURN, function (Action $action) {
                return $action
                    ->setLabel('Sauvegarder et Retourner')  // Personnalisation du texte du bouton
                    ->setIcon('fa fa-check');  // Icône personnalisée pour l'action
            });

        // Personnalisation de l'action "saveAndContinue" dans la page d'édition
        $actions = $actions
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE, function (Action $action) {
                return $action->setLabel('Sauvegarder et Continuer');  // Personnalisation de l'action
            });

        return $actions;
    }






    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
