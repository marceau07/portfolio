<?php

namespace App\Security;

use App\Entity\LoginAttempt;
use App\Entity\User;
use App\Repository\LoginAttemptRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use App\Repository\UserRepository;

class ConnectFormAuthenticator extends AbstractFormLoginAuthenticator implements PasswordAuthenticatedInterface {

    use TargetPathTrait;

    private $entityManager;
    private $urlGenerator;
    private $csrfTokenManager;
    private $passwordEncoder;
    private $loginAttemptRepository;
    private $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder, LoginAttemptRepository $loginAttemptRepository, UserRepository $userRepository) {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->loginAttemptRepository = $loginAttemptRepository;
        $this->userRepository = $userRepository;
    }

    public function supports(Request $request) {
        return 'app_login' === $request->attributes->get('_route') && $request->isMethod('POST');
    }

    public function getCredentials(Request $request) {
        $credentials = [
            'username' => $request->request->get('username'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
                Security::LAST_USERNAME, $credentials['username']
        );

        // On sauvegarde une tentative de connexion
        $newLoginAttempt = new LoginAttempt($request->getClientIp(), $credentials['username']);

        $this->entityManager->persist($newLoginAttempt);
        $this->entityManager->flush();

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider) {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $credentials['username']]);

        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Username could not be found.');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user) {
        // Vérification
        if ($this->loginAttemptRepository->countRecentLoginAttempts($credentials['username']) > 3) {
            // CustomUserMessageAuthenticationException nous permet de définir nous-même le message,
            // qui sera affiché à l'utilisateur (ou bien sa clef de traduction).
            // Attention toutefois à ne pas révéler trop d'informations dans le messages,
            // notamment ne pas indiquer si le compte existe.
            $anUser = $this->userRepository->getEmailLoginFailed($credentials['username']);
            
//Envoie d'un mail//
            $header = "MIME-Version: 1.0\r\n";
            $header .= 'From:"innovanglais.fr"<no-reply@symfony4.4018.fr>' . "\n";
            $header .= 'Content-Type:text/html; charset="uft-8"' . "\n";
            $header .= 'Content-Transfer-Encoding: 8bit';

            $message = "
                    <html>
                        <body>
                            <div align='center'>
                                <p>Nous avons détecté une activité suspecte sur votre compte.</p>
                                <p>Est-ce que vous avez récemment tenté de vous connecter ?</p>
                                <a href='#'>Oui</a><a href='http://serveur1.arras-sio.com/symfony4-4018/InnovAnglais/public/forgotten_password'>Non</a>
                            </div>
                        </body>
                    </html>
                   ";

            mail($anUser['email'], "It was you ?", $message, $header);
//fin d'envoie du mail//
            throw new CustomUserMessageAuthenticationException('Vous avez essayé de vous connecter avec un mot'
                    . ' de passe incorrect de trop nombreuses fois. Veuillez patienter ' . $this->loginAttemptRepository::DELAY_IN_MINUTES . ' minutes avant de ré-essayer.'
                    . ' Consultez votre adresse mail: ' . $anUser['email'] . ' pour plus d\'informations.');
        }

        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function getPassword($credentials): ?string {
        return $credentials['password'];
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey) {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }
        return new RedirectResponse($this->urlGenerator->generate('accueil'));
        // For example : return new RedirectResponse($this->urlGenerator->generate('some_route'));
        throw new \Exception('TODO: provide a valid redirect inside ' . __FILE__);
    }

    protected function getLoginUrl() {
        return $this->urlGenerator->generate('app_login');
    }

}
