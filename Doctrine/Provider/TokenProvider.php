<?php

namespace Bazinga\OAuthServerBundle\Doctrine\Provider;

use Bazinga\OAuthServerBundle\Model\Provider\TokenProvider as BaseTokenProvider;
use Bazinga\OAuthServerBundle\Model\TokenInterface;
use Doctrine\ORM\EntityManager;

/**
 * @author Robin van der Vleuten <robinvdvleuten@gmail.com>
 */
class TokenProvider extends BaseTokenProvider
{
    /**
     * ObjectRepository
     */
    private $requestTokenRepository;

    /**
     * ObjectRepository
     */
    private $accessTokenRepository;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * Constructor
     *
     * @param EntityManager $entityManager An EntityManager instance.
     * @param string        $accessTokenClass
     * @param string        $requestTokenClass
     */
    public function __construct(EntityManager $entityManager, $requestTokenClass, $accessTokenClass)
    {
        $this->entityManager = $entityManager;

        $this->requestTokenRepository = $entityManager->getRepository($requestTokenClass);
        $this->accessTokenRepository = $entityManager->getRepository($accessTokenClass);

        parent::__construct($requestTokenClass, $accessTokenClass);
    }

    /**
     * {@inheritDoc}
     */
    public function loadRequestTokenBy(array $criteria)
    {
        return $this->requestTokenRepository->findOneBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function loadRequestTokens()
    {
        return $this->requestTokenRepository->findAll();
    }

    /**
     * {@inheritDoc}
     */
    public function loadAccessTokenBy(array $criteria)
    {
        return $this->accessTokenRepository->findOneBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function loadAccessTokens()
    {
        return $this->requestTokenRepository->findAll();
    }

    /**
     * {@inheritDoc}
     */
    public function deleteToken(TokenInterface $token)
    {
        $this->entityManager->remove($token);
        $this->entityManager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function updateToken(TokenInterface $token)
    {
        $this->entityManager->persist($token);
        $this->entityManager->flush();
    }
}
