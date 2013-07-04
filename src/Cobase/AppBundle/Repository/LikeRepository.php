<?php
namespace Cobase\AppBundle\Repository;

use Cobase\AppBundle\Entity\Like;
use Cobase\AppBundle\Entity\Post;
use Cobase\UserBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class LikeRepository extends EntityRepository
{
    /**
     * @param Post $post
     * @param User $user
     *
     * @return Like
     */
    public function getPostSpecificLikeByUser(Post $post, User $user)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('l')
            ->from('Cobase\AppBundle\Entity\Like', 'l')
            ->leftJoin('l.likings', 'lk')
            ->where('lk.resourceId = :id')
            ->setParameter('id', $post->getId())
            ->andWhere('lk.resourceType = :type')
            ->setParameter('type', 'post')
            ->andWhere('l.user = :user')
            ->setParameter('user', $user);

        try {
            return $qb->getQuery()->getSingleResult();
        } catch(NoResultException $e) {
            return null;
        }
    }
}
