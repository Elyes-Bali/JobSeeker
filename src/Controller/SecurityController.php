<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Security $security): Response
    {
        if ($this->getUser()) {
            // Vérification des rôles de l'utilisateur
            if ($security->isGranted('ROLE_ADMIN') || $security->isGranted('ROLE_SUPER_ADMIN')) {
                return $this->redirectToRoute('dash'); // Redirige vers l'interface adminTest
            }

            return $this->redirectToRoute('app_home_page'); // Redirige vers l'interface utilisateur
        }

        // Afficher le formulaire de connexion
        return $this->render('security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
