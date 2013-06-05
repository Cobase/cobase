<?php

namespace Cobase\AppBundle\Service;

use Doctrine\ORM\EntityManager,
    Doctrine\ORM\EntityRepository,
    Symfony\Component\Security\Core\SecurityContext,
    Cobase\AppBundle\Entity\Event,
    Cobase\AppBundle\Entity\Highfive,
    Cobase\AppBundle\Entity\QuickHighfive,
    Cobase\UserBundle\Entity\User;


class HighfiveService
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var HighfiveRepository
     */
    protected $repository;

    /**
     * @var QuickHighfiveRepository
     */
    protected $quickRepository;

    /**
     * @var SecurityContext
     */
    protected $security;

    /**
     * @param EntityManager    $em
     * @param EntityRepository $repository
     * @param SecurityContext  $security
     */
    public function __construct(EntityManager $em, EntityRepository $repository, SecurityContext $security)
    {
        $this->em               = $em;
        $this->repository       = $repository;
        $this->security         = $security;
    }

    /**
     * @return array
     */
    public function getHighfives()
    {
        return $this->repository->findAll();
    }

    /**
     * @return array
     */

    /**
     * Get all latest highfives for given user
     *
     * @param null $limit
     * @return mixed
     */
    public function getLatestHighfivesForPublicEvents($limit = null)
    {
        return $this->repository->getLatestHighfivesForPublicEvents($limit);
    }

    /**
     * @return array
     */
    public function getAllHighfivesforPublicEvents($limit = null, $order)
    {
        return $this->repository->getAllHighfivesforPublicEvents($limit, $order);
    }

    /**
     * @param  int $id
     * @return Event
     */
    public function getHighfiveById($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Check to see if a user already has given a high five for an event
     *
     * @param \Cobase\AppBundle\Entity\Event $event
     * @param \Cobase\UserBundle\Entity\User $user
     * @return bool
     */
    public function hasUserSubmittedHighfiveForEvent(Event $event, User $user)
    {
        $result = $this->repository->findBy(
            array(
                'user'  => $user,
                'event' => $event
            )
        );

        return sizeOf($result) > 0 ? true : false;
    }

    /**
     * Save high five
     *
     * @param  Highfive $highfive
     * @param  Event $event
     * @param  User  $user
     * @return Event
     */
    public function saveHighfive(Highfive $highfive, Event $event, User $user = null)
    {
        if (!$user) {
            $user = $this->security->getToken()->getUser();
        }

        $highfive->setUser($user);
        $highfive->setEvent($event);

        $this->em->persist($highfive);
        $this->em->flush();

        return $highfive;
    }

    /**
     * Save quick high five
     *
     * @param  QuickHighfive $highfive
     * @param  Event $event
     * @param  User  $user
     * @return Event
     */
    public function saveQuickHighfive(QuickHighfive $quickHighfive)
    {
        $this->em->persist($quickHighfive);
        $this->em->flush();

        return $quickHighfive;
    }

    /**
     * Get all high fives for current user
     *
     * @return array
     */
    public function getHighfivesForUser($user)
    {
        return $this->repository->findAllForUser($user);
    }

    /**
     * Get all quick high fives for current user
     *
     * @return array
     */
    public function getAllQuickHighfivesForUser(User $user, $limit, $order)
    {
        return $this->repository->getAllQuickHighfivesForUser($user, $limit, $order);
    }

}
