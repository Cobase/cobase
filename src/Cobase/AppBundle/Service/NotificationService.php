<?php
namespace Cobase\AppBundle\Service;

use Cobase\AppBundle\Entity\Group;
use Cobase\AppBundle\Entity\Notification;
use Cobase\AppBundle\Entity\Post;
use Cobase\AppBundle\Entity\PostEvent;
use Cobase\AppBundle\Repository\NotificationRepository;
use Cobase\UserBundle\Entity\User;

use Doctrine\ORM\EntityManager;

class NotificationService
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var NotificationRepository
     */
    protected $notificationRepository;

    /**
     * @param EntityManager    $em
     * @param NotificationRepository $repository
     */
    public function __construct(EntityManager $em, $notificationRepository)
    {
        $this->em                       = $em;
        $this->notificationRepository  = $notificationRepository;
    }

    /**
     * @param Group $group
     * @param User $user
     *
     * return $bool
     */
    public function isUserUserNotifiedOfNewGroupPosts(Group $group, User $user)
    {
        return $this->notificationRepository->isUserUserNotifiedOfNewGroupPosts($group, $user);
    }

    /**
     * @param Group $group
     * @param User $user
     *
     * @return Notification
     */
    public function setUserToBeNotifiedOfNewGroupPosts(Group $group, User $user)
    {
        $notification = new Notification($user, $group);

        $this->em->persist($notification);
        $this->em->flush();

        return $notification;
    }

    /**
     * @param Group $group
     * @param User $user
     */
    public function setUserNotToBeNotifiedOfNewGroupPosts(Group $group, User $user)
    {
        $this->notificationRepository->setUserNotToBeNotifiedOfNewGroupPosts($group, $user);
    }

    /**
     * @param Post $post
     */
    public function newPostAdded(Post $post)
    {
        $group = $post->getGroup();

        if (null !== $group) {
            $postEvent = new PostEvent($group);
            $this->em->persist($postEvent);
            $this->em->flush();
        }
    }
}
