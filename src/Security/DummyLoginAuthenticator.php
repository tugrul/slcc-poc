<?php

namespace App\Security;

use App\Entity\User;
use App\Form\LoginFormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class DummyLoginAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    /**
     * @var UrlGeneratorInterface
     */
    protected $urlGenerator;

    /**
     * @var FormInterface
     */
    protected $loginForm;

    public function __construct(UrlGeneratorInterface $urlGenerator, FormFactoryInterface $formFactory)
    {
        $this->urlGenerator = $urlGenerator;

        $this->loginForm = $formFactory->create(LoginFormType::class);
    }

    public function supports(Request $request)
    {
        if ('user_login' !== $request->attributes->get('_route')) {
            return false;
        }

        $this->loginForm->handleRequest($request);

        return $this->loginForm->isSubmitted() && $this->loginForm->isValid();
    }

    public function getCredentials(Request $request)
    {
        return $this->loginForm->getData();
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return new User($credentials['username']);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('blog_index'));
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate('user_login');
    }


    public function getLoginForm()
    {
        return $this->loginForm;
    }
}
