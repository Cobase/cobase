<?php

namespace Cobase\AppBundle\Repository;

use Cobase\AppBundle\Entity\Post;
use Doctrine\ORM\EntityRepository;
use Cobase\UserBundle\Entity\User;

/**
 * PostRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostRepository extends EntityRepository
{
    /**
     * Get given amount of latest posts for public groups for any user
     *
     * @param null $limit
     * @return array
     */
    public function getLatestPostsForPublicGroups($limit = null)
    {
        $qb = $this->createQueryBuilder('b')
            ->select('b, c')
            ->leftJoin('b.group', 'c')
            ->addOrderBy('b.created', 'DESC')
            ->andWhere('c.isPublic = ?1')
            ->setParameter('1', '1');

        if (false === is_null($limit))
            $qb->setMaxResults($limit);

        return $qb->getQuery()
            ->getResult();
    }

    /**
     * Get all group posts for public groups with given sort options
     *
     * @param null $limit
     * @param string $order
     * @return array
     */
    public function getAllPostsForPublicGroups($limit = null, $order = 'ASC')
    {
        $qb = $this->createQueryBuilder('b')
            ->select('b, c')
            ->leftJoin('b.group', 'c')
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
     * Find latest group posts for a given user
     *
     * @param \Cobase\UserBundle\Entity\User $user
     * @return array
     */
    public function findAllForUser(User $user)
    {
        $qb = $this->createQueryBuilder('b')
            ->select('b, c')
            ->leftJoin('b.group', 'c')
            ->addOrderBy('b.created', 'DESC')
            ->andWhere('c.user = ?1')
            ->setParameter('1', $user);

        return $qb->getQuery()
            ->getResult();
    }

    /**
     * Get all quick posts for a given user with sort options
     *
     * @param \Cobase\UserBundle\Entity\User $user
     * @param $limit
     * @param $order
     * @return array
     */
    public function getAllQuickPostsForUser(User $user, $limit, $order)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT u
                           FROM Cobase\AppBundle\Entity\QuickPost u
                           WHERE u.user = ?1
                           ORDER BY u.created ' . $order)
            ->setParameter('1', $user);

        return $query->getResult();
    }

    /**
     * @param Post $post
     *
     * @return integer
     */
    public function getLikeCount(Post $post)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('count(l)')
            ->from('Cobase\AppBundle\Entity\Like', 'l')
            ->where('l.resourceId = :id')
            ->setParameter('id', $post->getId())
            ->andWhere('l.resourceType = :type')
            ->setParameter('type', 'post');

        try {
            return $qb->getQuery()->getSingleScalarResult();
        } catch(NoResultException $e) {
            return 0;
        }
    }
}
