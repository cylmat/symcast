<?php

namespace SecurityAuth\Security;

use SecurityAuth\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    // Can retrieve previous page
    use TargetPathTrait;

    private $userRepository;
    private $router;
    private $csrfManager;
    private $passEncoder;

    public function __construct(
        UserRepository $userRepository, 
        RouterInterface $router,
        CsrfTokenManagerInterface $csrfManager,
        UserPasswordEncoderInterface $passEncoder
    ) {
        $this->userRepository = $userRepository;
        $this->router = $router;
        $this->csrfManager = $csrfManager;
        $this->passEncoder = $passEncoder;
    }

    protected function getLoginUrl()
    {
        return $this->router->generate('security_login');
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request)
    {
        // if TRUE: call getCredentials()
        return $request->attributes->get('_route') === 'security_login' &&
            $request->isMethod('POST');
    }

    // called if supports() is true
    public function getCredentials(Request $request): array
    {
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $request->request->get('email')
        );

        return [
            'emailing' => $request->request->get('email'),
            'passwording' => $request->request->get('password'),

            'csrf_' => $request->request->get('_csrf_token_form')
        ];
    }

    // credentials are from getCredentials()
    // calls checkCredentials() if User is returned
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        // Own exception
        if ($credentials['emailing'] == 'try@except.aaa') {
            throw new CustomUserMessageAuthenticationException('Invalid credentials customizing');
        }

        // Csrf
        $token = new CsrfToken('authenticating', $credentials['csrf_']);
        if (!$this->csrfManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        return $this->userRepository->findOneByEmail($credentials['emailing']);
    }

    // Return `true` to cause authentication success
    public function checkCredentials($credentials, UserInterface $user)
    {
        //return boolval(preg_match('/example\.com/', $credentials['emailing']));

        return $this->passEncoder->isPasswordValid($user, $credentials['passwording']);
    }

    /*
        call getLoginUrl() in parent::
        failed in getUser() : Username could not be found.
        or 
        checkCredentials() : Invalid credentials.
        
        errors from Ctrl::$authenticationUtils->getLastAuthenticationError()
    */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        parent::onAuthenticationFailure($request, $exception);
    }

    // providerKey: 'main'
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        // redirect to previous asked path if redirected to login
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        // fallback to default page
        return new RedirectResponse($this->router->generate('security_admin')); 
    }

    
    /**
     * Called when authentication is needed, but it's not sent
     */
    /**
     * Called when Anonymous, not logged in yet
     * ..redirect to getLoginUrl()
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return parent::start($request, $authException);
    }

    // Mandatory to use the feature
    public function supportsRememberMe()
    {
        return parent::supportsRememberMe();
    }
}
