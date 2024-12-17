<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[IsGranted('ROLE_SUPER_ADMIN')]
    #[Route('/addAdmin', name: 'add_admin')]
    public function addAdmin(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        // Vérification du rôle super-adminTest
        if (!$this->isGranted('ROLE_SUPER_ADMIN')) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette page.');
        }

        $user = new User();
        $form = $this->createForm(AdminUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer le mot de passe en clair depuis le formulaire
            $plainPassword = $form->get('plainPassword')->getData();

            // Vérifier que le mot de passe est bien renseigné
            if (!$plainPassword) {
                throw new InvalidArgumentException('Le mot de passe est obligatoire.');
            }

            // Hacher le mot de passe
            $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);

            // Définir le rôle administrateur
            $user->setRoles(['ROLE_ADMIN']);

            // Sauvegarder l'utilisateur dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Message de confirmation
            $this->addFlash('success', 'Nouvel administrateur ajouté avec succès.');

            // Redirection vers le tableau de bord
            return $this->redirectToRoute('admin_dashboard');
        }


        return $this->render('user/addAdmin.html.twig', [
            'adminForm' => $form->createView(),
        ]);
    }


    #[Route('/users', name: 'app_user_list', methods: ['GET', 'POST'])]
    public function listUsers(Request $request, UserRepository $userRepository): Response
    {
        // Création du formulaire de filtre et de recherche
        $form = $this->createFormBuilder()
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Tous les utilisateurs' => null,
                    'Administrateurs' => 'ROLE_ADMIN',
                    'Utilisateurs' => 'ROLE_USER',
                ],
                'required' => false,
                'placeholder' => 'Filtrer par rôle',
            ])
            ->add('search', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher par nom ou email',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher',
            ])
            ->getForm();

        $form->handleRequest($request);

        $users = $userRepository->findAll(); // Par défaut, afficher tous les utilisateurs

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération des données du formulaire
            $filters = $form->getData();
            $role = $filters['role'] ?? null;
            $search = $filters['search'] ?? null;

            // Construction de la requête de recherche
            $queryBuilder = $userRepository->createQueryBuilder('u');

            if ($role) {
                $queryBuilder->andWhere('u.roles LIKE :role')
                    ->setParameter('role', '%"'.$role.'"%');
            }

            if ($search) {
                $queryBuilder->andWhere('u.nom LIKE :search OR u.email LIKE :search')
                    ->setParameter('search', '%' . $search . '%');
            }

            $users = $queryBuilder->getQuery()->getResult();
        }

        return $this->render('user/list.html.twig.twig', [
            'form' => $form->createView(),
            'users' => $users,
        ]);
    }




}
