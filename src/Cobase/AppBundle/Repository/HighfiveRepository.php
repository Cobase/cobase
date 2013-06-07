<?php

namespace Cobase\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Cobase\UserBundle\Entity\User;

/**
 * HighfiveRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class HighfiveRepository extends EntityRepository
{
    /**
     * Get given amount of latest high fives for public events for any user
     *
     * @param null $limit
     * @return array
     */
    public function getLatestHighfivesForPublicEvents($limit = null)
    {
        $qb = $this->createQueryBuilder('b')
            ->select('b, c')
            ->leftJoin('b.event', 'c')
            ->addOrderBy('b.created', 'DESC')
            ->andWhere('c.isPublic = ?1')
            ->setParameter('1', '1');

        if (false === is_null($limit))
            $qb->setMaxResults($limit);

        return $qb->getQuery()
            ->getResult();
    }

    /**
     * Get all event high fives for public events with given sort options
     *
     * @param null $limit
     * @param string $order
     * @return array
     */
    public function getAllHighfivesForPublicEvents($limit = null, $order = 'ASC')
    {
        $qb = $this->createQueryBuilder('b')
            ->select('b, c')
            ->leftJoin('b.event', 'c')
            ->addOrderBy('b.created', $order)
            ->andWhere('c.isPublic = ?1')
            ->setParameter('1', '1');

        if ($limit !== null) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()
            ->getResult();
    }

    /**
     * Find latest event high fives for a given user
     *
     * @param \Cobase\UserBundle\Entity\User $user
     * @return array
     */
    public function findAllForUser(User $user)
    {
        $qb = $this->createQueryBuilder('b')
            ->select('b, c')
            ->leftJoin('b.event', 'c')
            ->addOrderBy('b.created', 'DESC')
            ->andWhere('c.user = ?1')
            ->setParameter('1', $user);

        return $qb->getQuery()
            ->getResult();
    }

    /**
     * Get all quick high fives for a given user with sort options
     *
     * @param \Cobase\UserBundle\Entity\User $user
     * @param $limit
     * @param $order
     * @return array
     */
    public function getAllQuickHighfivesForUser(User $user, $limit, $order)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT u
                           FROM Cobase\AppBundle\Entity\QuickHighfive u
                           WHERE u.user = ?1
                           ORDER BY u.created ' . $order)
            ->setParameter('1', $user);

        return $query->getResult();
    }
}