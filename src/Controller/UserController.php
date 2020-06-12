<?php


namespace App\Controller;

use App\Security\DummyLoginAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/login", name="user_login")
     * @param DummyLoginAuthenticator $authenticator
     * @return Response
     */
    public function loginAction(DummyLoginAuthenticator $authenticator)
    {
        return $this->render('user/login.html.twig', [
            'loginForm' => $authenticator->getLoginForm()->createView()
        ]);
    }
}