<?php

namespace App\Controller;

use App\Form\ReclamationType;
use App\Entity\Reclamation;
use App\Repository\ReclamationRepository;
use DateTime;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Security;


class ReclamationController extends AbstractController
{
    #[Route('/reclamation', name: 'app_reclamation')]
        public function index(): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'controller_name' => 'ReclamationController',
        ]);
    }

    #[Route('/addReclamation', name: 'app_add_reclamation')]
    public function addReclamation(Request $request,ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine ->getManager();


        $reclamation = new Reclamation();
        $entityManager->persist($reclamation);

        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $reclamation->setUser($this->getUser());
            $reclamation->setDate(date: new DateTime());
            $reclamation = $form->getData();
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_list');
        }
        return $this->render('reclamation/addReclamation.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/reclamations', name: 'app_reclamation_list', methods: ['GET'])]
    public function listReclamations(
        Request $request,
        FormFactoryInterface $formFactory,
        ReclamationRepository $reclamationRepository,
        Security $security
    ): Response {
        $user = $this->getUser();
        $isAdmin = $security->isGranted('ROLE_ADMIN') || $security->isGranted('ROLE_SUPER_ADMIN');

        // Création du formulaire de filtre
        $form = $formFactory->createBuilder()
            ->setMethod('GET')
            ->add('priorite', ChoiceType::class, [
                'label' => 'Priorité',
                'choices' => [
                    'Toutes' => null,
                    'Urgent' => 'Urgent',
                    'Moyen' => 'Moyen',
                    'Bas' => 'Bas',
                ],
                'required' => false,
                'placeholder' => 'Filtrer par priorité',
            ])
            ->add('categorie', ChoiceType::class, [
                'label' => 'Catégorie',
                'choices' => [
                    'Toutes' => null,
                    'Problèmes de paiement' => 'Problèmes de paiement',
                    'Conduite inappropriée d\'un conducteur' => 'Conduite inappropriée d\'un conducteur',
                    'Passager non ponctuel' => 'Passager non ponctuel',
                    'Autres' => 'Autres',
                ],
                'required' => false,
                'placeholder' => 'Filtrer par catégorie',
            ])
            ->getForm();

        $form->handleRequest($request);

        // Initialisation des filtres
        $criteria = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $priorite = $form->get('priorite')->getData();
            $categorie = $form->get('categorie')->getData();

            if ($priorite) {
                $criteria['priorite'] = $priorite;
            }

            if ($categorie) {
                $criteria['categorie'] = $categorie;
            }
        }

        // Filtrage selon l'utilisateur connecté ou accès admin
        if (!$isAdmin) {
            $criteria['user'] = $user;
        }

        // Récupérer les réclamations filtrées depuis le repository
        $reclamations = $reclamationRepository->findBy($criteria);

        return $this->render('reclamation/list.html.twig', [
            'form' => $form->createView(),
            'reclamations' => $reclamations,
        ]);
    }


//    public function index(ReclamationRepository $reclamationRepository): Response
//    {
//        // Récupérer les réclamations triées par priorité
//        $reclamations = $reclamationRepository->findBy([], ['priorite' => 'ASC']); // Urgent > Moyen > Bas
//
//        return $this->render('reclamation/index.html.twig', [
//            'reclamations' => $reclamations,
//        ]);
//    }

    #[Route('/reclamation/edit/{id}', name: 'app_reclamation_edit')]
    public function editReclamation(ManagerRegistry $doctrine, Request $request, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $reclamation = $entityManager->getRepository(Reclamation::class)->find($id);

        if (!$reclamation) {
            throw $this->createNotFoundException('Réclamation introuvable.');
        }

        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_list');
        }

        return $this->render('reclamation/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/reclamation/delete/{id}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function deleteReclamation(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $reclamation = $entityManager->getRepository(Reclamation::class)->find($id);

        if (!$reclamation) {
            throw $this->createNotFoundException('Réclamation introuvable.');
        }

        $entityManager->remove($reclamation);
        $entityManager->flush();

        return $this->redirectToRoute('app_reclamation_list');
    }

    #[Route('show/{id}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }






}
