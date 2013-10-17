<?php
namespace Cobase\AppBundle\Repository;

use Cobase\AppBundle\Entity\Group;
use Cobase\UserBundle\Entity\User;

use Doctrine\ORM\EntityRepository;

class NotificationRepository extends EntityRepository
{
    /**
     * @param Group $group
     * @param User $user
     *
     * return $bool
     */
    public function isUserUserNotifiedOfNewGroupPosts(Group $group, User $user)
    {
        $qb = $this->createQueryBuilder('n')
            ->select('n')
            ->where('n.user = :user')
            ->andWhere('n.group = :group')
            ->setParameter('user', $user)
            ->setParameter('group', $group);

        $result = $qb->getQuery()->getResult();

        return count($result) == 1;
    }

    /**
     * @param Group $group
     * @param User $user
     */
    public function setUserNotToBeNotifiedOfNewGroupPosts(Group $group, User $user)
    {
        $qb = $this->createQueryBuilder('n')
            ->delete('Cobase\AppBundle\Entity\Notification', 'n')
            ->where('n.user = :user')
            ->andWhere('n.group = :group')
            ->setParameter('user', $user)
            ->setParameter('group', $group);

        $qb->getQuery()->execute();
    }

    /**
     * @param int $amount
     *
     * @return array
     */
    public function getNewPosts($amount = 30)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('pe')
            ->from('Cobase\AppBundle\Entity\PostEvent', 'pe')
            ->setMaxResults($amount)
            ->orderBy('pe.id', 'ASC');

        return $qb->getQuery()->execute();
    }

    /**
     * @param Group $group
     *
     * @return array
     */
    public function getNotificationsFor(Group $group)
    {
        $qb = $this->createQueryBuilder('n')
            ->select('n')
            ->where('n.group = :group')
            ->setParameter('group', $group);

        return $qb->getQuery()->execute();
    }
}
