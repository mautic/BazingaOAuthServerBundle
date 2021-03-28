<?php

namespace Bazinga\OAuthServerBundle\Doctrine\Provider;

use Bazinga\OAuthServerBundle\Model\ConsumerInterface;
use Bazinga\OAuthServerBundle\Model\Provider\ConsumerProvider as BaseConsumerProvider;
use Doctrine\ORM\EntityManager;

/**
 * @author Robin van der Vleuten <robinvdvleuten@gmail.com>
 */
class ConsumerProvider extends BaseConsumerProvider
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * ObjectRepository
     */
    private $repository;

    /**
     * Constructor
     *
     * @param EntityManager $entityManager An EntityManager instance.
     * @param string        $consumerClass
     */
    public function __construct(EntityManager $entityManager, $consumerClass)
    {
        $this->entityManager = $entityManager;

        $this->repository = $entityManager->getRepository($consumerClass);

        parent::__construct($consumerClass);
    }

    /**
     * {@inheritDoc}
     */
    public function getConsumerBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function deleteConsumer(ConsumerInterface $consumer)
    {
        $this->entityManager->remove($consumer);
        $this->entityManager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function updateConsumer(ConsumerInterface $consumer)
    {
        $this->entityManager->persist($consumer);
        $this->entityManager->flush();
    }
}
