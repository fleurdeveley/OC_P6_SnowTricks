<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'security_login';

    private $urlGenerator;
    private $hasher;
    private $entityManager;
    private $csrfTokenManager;

    public function __construct(EntityManagerInterface $entityManager, 
    UrlGeneratorInterface $urlGenerator, UserPasswordHasherInterface $hasher, 
    CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->hasher = $hasher;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    // Si le formulaire de connexion est soumis
    public function supports(Request $request): bool
    {
        return self::LOGIN_ROUTE === $request->attributes->get('_route') 
            && $request->isMethod('POST');
    }

    // récupérer les informations de connexion à partir de la request
    public function getCredentials(Request $request) 
    {
        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];

        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;
    }

    // grace aux informations du credentials, est ce que j'ai l'email de l'utilisateur dans ma bdd
    public function getUser($credentials): ?User
    {
        // identifiant d'accès, propre à chaque utilisateur, impossible de le pirater
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            'email' => $credentials['email'],
            'activated' => true
        ]);

        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException("L'email n'existe pas ou le compte 
            n'est pas actif.");
        }

        return $user;
    }

    // vérifier que le mot de passe fournit de credentials soit bien celui de l'utilisateur
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return $this->hasher->isPasswordValid($user, $credentials['password']);
    }

    // récupérer le mot de passe dans le tableau crédentials
    public function getPassword($credentials): ?string
    {
        return $credentials['password'];
    }

    // création de l'authentification
    public function authenticate(Request $request): PassportInterface
    {
        $credentials = $this->getCredentials($request);

        $user = $this->getUser($credentials);

        $email = $user->getEmail();

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        // classe contenant des informations à valider durant l'authentification
        return new Passport(
            // cheker l'email
            new UserBadge($email),

            // checker le password
            new PasswordCredentials($request->request->get('password', '')),
            [
                // checker la validité du csrfToken
                new CsrfTokenBadge('authenticate', $request->get('_csrf_token')),
            ]
        );
    }

    // si l'email et le mot de passe sont bon, alors l'utilisateur est connecté puis redirogé vers la page accueil
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, 
    string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('homepage'));
    }

    // retourner l'url de la route login
    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
