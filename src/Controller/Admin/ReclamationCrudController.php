<?php

namespace App\Controller\Admin;

use App\Entity\Reclamation;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class ReclamationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reclamation::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Réclamation')
            ->setEntityLabelInPlural('Réclamations')
            ->setDefaultSort(['id' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(), // L'ID est masqué dans le formulaire
            TextField::new('titre', 'Titre'),
            TextField::new('description', 'Description'),
            DateTimeField::new('date', 'Date de création')->setFormat('dd/MM/yyyy HH:mm'),
            ChoiceField::new('priorite', 'Priorité')->setChoices([
                'Urgent' => 'Urgent',
                'Moyen' => 'Moyen',
                'Bas' => 'Bas',
            ]),
            TextField::new('categorie', 'Catégorie'),
            ChoiceField::new('statut')
                ->setChoices([
                    'Ouvert' => 'Ouvert',
                    'En cours' => 'En cours',
                    'Résolu' => 'Résolu',
                    'Rejeté' => 'Rejeté',
                ])
                ->setLabel('Statut')
                ->setFormTypeOption('placeholder', 'Choisir un statut') // Ajout d'un placeholder
                ->setRequired(true),
            AssociationField::new('user', 'Utilisateur')
                ->setFormTypeOptions(['choice_label' => 'nom']),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $repondreAction = Action::new('repondre', 'Répondre', 'fa fa-reply')
            ->linkToRoute('app_reponse_new', function (Reclamation $entity) {
                return [
                    'id' => $entity->getId(),
                ];
            });

        return $actions
            ->add(Crud::PAGE_INDEX, $repondreAction);
//            ->disable(Action::DELETE)
//            ->disable(Action::NEW);
    }
}
