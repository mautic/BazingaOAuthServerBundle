<?php

namespace Bazinga\OAuthServerBundle\Controller;

use Bazinga\OAuthServerBundle\Model\RequestTokenInterface;
use Bazinga\OAuthServerBundle\Model\Provider\TokenProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Symfony\Component\Templating\EngineInterface;

/**
 * LoginController: this controller must be secured to get a valid user.
 *
 * @author William DURAND <william.durand1@gmail.com>
 */
class LoginController
{
    /**
     * @var EngineInterface
     */
    protected $engine;

    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var AuthorizationCheckerInterface
     */
    protected $authorizationChecker;

    /**
     * @var TokenProviderInterface
     */
    protected $tokenProvider;

    public function __construct(EngineInterface $engine, TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authorizationChecker, TokenProviderInterface $tokenProvider)
    {
        $this->engine               = $engine;
        $this->tokenStorage         = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenProvider        = $tokenProvider;
    }

    /**
     * Present a form to the user to accept or not to share
     * its information with the consumer.
     */
    public function allowAction(Request $request)
    {
        $oauth_token    = $request->get('oauth_token', null);
        $oauth_callback = $request->get('oauth_callback', null);

        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            $token = $this->tokenProvider->loadRequestTokenByToken($oauth_token);

            if ($token instanceof RequestTokenInterface) {
                $this->tokenProvider->setUserForRequestToken($token, $this->tokenStorage->getToken()->getUser());

                return new Response($this->engine->render('BazingaOAuthServerBundle::authorize.html.twig', array(
                    'consumer'       => $token->getConsumer(),
                    'oauth_token'    => $oauth_token,
                    'oauth_callback' => $oauth_callback
                )));
            }
        }

        throw new HttpException(404);
    }

    public function loginCheckAction()
    {
    }
}
