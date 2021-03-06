<?php

namespace AppBundle\Security;

use AppBundle\Enum\RolesEnum;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class AuthenticationHandler implements AuthenticationSuccessHandlerInterface
{
    /**
     * @var AuthorizationChecker
     */
    private $authChecker;

    /**
     * @var Router
     */
    private $router;

    /**
     * AuthenticationHandler constructor.
     * @param AuthorizationChecker $authChecker
     * @param Router $router
     */
    public function __construct(
        AuthorizationChecker $authChecker,
        Router $router
    ) {
        $this->authChecker = $authChecker;
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $url = $this->router->generate('easyadmin');
        if ($this->authChecker->isGranted(RolesEnum::APP_ADMIN)) {
            $url = $this->router->generate(
                'easyadmin',
                [
                    'action' => 'list',
                    'entity' => 'Course',
                ],
                UrlGeneratorInterface::ABSOLUTE_PATH
            );
        }
        return new RedirectResponse($url);
    }
}
