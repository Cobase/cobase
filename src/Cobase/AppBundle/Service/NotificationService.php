<?php
namespace Cobase\AppBundle\Service;

use Cobase\AppBundle\Entity\Group;
use Cobase\AppBundle\Entity\Notification;
use Cobase\AppBundle\Entity\Post;
use Cobase\AppBundle\Entity\PostEvent;
use Cobase\AppBundle\Repository\NotificationRepository;
use Cobase\Component\EmailTemplate;
use Cobase\UserBundle\Entity\User;

use Doctrine\ORM\EntityManager;

use Swift_Mailer;

use Swift_Message;

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
     * @var Swift_Mailer;
     */
    protected $mailer;

    /**
     * @param EntityManager             $em
     * @param NotificationRepository    $repository
     * @param Swift_Mailer              $mailer
     */
    public function __construct(EntityManager $em, $notificationRepository, Swift_mailer $mailer)
    {
        $this->em                       = $em;
        $this->notificationRepository   = $notificationRepository;
        $this->mailer                   = $mailer;
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

    /**
     * @var EmailTemplate $emailTemplate
     * @var int $amount
     *
     * @return array
     */
    public function notifyOfNewPosts(EmailTemplate $emailTemplate, $amount = 20)
    {
        $newPosts = $this->notificationRepository->getGroupsWithNewPosts($amount);

        foreach ($newPosts as $event) {
            $group = $event->getGroup();

            $notifications = $this->notificationRepository->getNotificationsFor($group);

            foreach ($notifications as $notification) {
                $userToNotify = $notification->getUser();

                $data = [];

                $message = Swift_Message::newInstance()
                    ->setSubject('Hello Email')
                    ->setFrom('send@example.com')
                    ->setTo($userToNotify->getEmail())
                    ->setBody($emailTemplate->render($data));

                $this->mailer->send($message);
            }
        }
    }
}
