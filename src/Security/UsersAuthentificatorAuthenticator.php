<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class UsersAuthentificatorAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function authenticate(Request $request): Passport
    {
        // Récupérer les données du formulaire
        $email = $request->request->get('email', '');
        $password = $request->request->get('password', '');
        $csrfToken = $request->request->get('_csrf_token', '');

        // Enregistrer le dernier nom d'utilisateur saisi
        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

        // Créer et retourner le Passport
        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge('authenticate', $csrfToken),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): RedirectResponse
    {
        // Récupérer les rôles de l'utilisateur authentifié
        $roles = $token->getRoleNames();

        // Si l'utilisateur est un super adminTest, rediriger vers l'adminTest dashboard
        if (in_array('ROLE_SUPER_ADMIN', $roles)) {
            return new RedirectResponse($this->router->generate('admin_dashboard'));
        }

        // Si l'utilisateur est un adminTest, rediriger vers l'adminTest dashboard
        if (in_array('ROLE_ADMIN', $roles)) {
            return new RedirectResponse($this->router->generate('admin_dashboard'));
        }

        // Otherwise, redirect to the home page for a regular user
        return new RedirectResponse($this->router->generate('app_home_page'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->router->generate(self::LOGIN_ROUTE);
    }
}
