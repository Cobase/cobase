<?php
namespace Cobase\AppBundle\Service;

use Cobase\AppBundle\Entity\Group;
use Cobase\AppBundle\Entity\Notification;
use Cobase\AppBundle\Entity\Post;
use Cobase\AppBundle\Entity\PostEvent;
use Cobase\AppBundle\Repository\NotificationRepository;

use Cobase\Component\AppInfo;
use Cobase\Component\EmailTemplate;
use Cobase\UserBundle\Entity\User;

use Doctrine\ORM\EntityManager;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

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
     * @var Router
     */
    protected $router;

    /**
     * @var AppInfo
     */
    protected $appInfo;

    /**
     * @param EntityManager             $em
     * @param NotificationRepository    $repository
     * @param Swift_Mailer              $mailer
     * @param Router                    $router
     * @param AppInfo                   $appInfo
     */
    public function __construct(
        EntityManager $em,
        $notificationRepository,
        Swift_mailer $mailer,
        Router $router,
        AppInfo $appInfo)
    {
        $this->em                       = $em;
        $this->notificationRepository   = $notificationRepository;
        $this->mailer                   = $mailer;
        $this->router                   = $router;
        $this->appInfo                  = $appInfo;
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
        $postEvent = new PostEvent($post);
        $this->em->persist($postEvent);
        $this->em->flush();
    }

    /**
     * @var EmailTemplate $emailTemplate
     * @var int $amount
     *
     * @return array
     */
    public function notifyOfNewPosts(EmailTemplate $emailTemplate, $amount = 20)
    {
        $newPosts = $this->notificationRepository->getNewPosts($amount);
        foreach ($newPosts as $event) {
            $post = $event->getPost();
            $group = $event->getGroup();
            $emailTemplate->setSubject('New post in group ' . $group->getTitle());

            $notifications = $this->notificationRepository->getNotificationsFor($group);

            foreach ($notifications as $notification) {
                $userToNotify = $notification->getUser();

                if ($userToNotify !== $post->getUser() ) {
                    $data = [
                        'name'              => $userToNotify->getName(),
                        'groupName'         => $group->getTitle(),
                        'submitterName'     => $post->getUser()->getName(),
                        'groupUrl'          => $this->router->generate(
                            'CobaseAppBundle_group_view',
                            ['groupId' => $group->getShortUrl()],
                            true
                        ),
                        'removeNotificationUrl' => $this->router->generate(
                            'CobaseAppBundle_group_unnotify',
                            ['groupId' => $group->getShortUrl()],
                            true
                        ),

                        'siteTitle' => $this->appInfo->getSiteName(),
                    ];

                    try {
                        $this->sendNotificationEmail($emailTemplate, $userToNotify, $data);
                    } catch (\Exception $e) {
                        // ignored on purpose
                    }
                }
            }

            $this->em->remove($event);
        }

        $this->em->flush();
    }

    /**
     * @param EmailTemplate $emailTemplate
     * @param User $userToNotify
     * @param array $data
     */
    protected function sendNotificationEmail(EmailTemplate $emailTemplate, User $userToNotify, array $data)
    {
        $message = Swift_Message::newInstance()
            ->setSubject($emailTemplate->getSubject())
            ->setFrom($this->appInfo->getSiteAdmin()->getAddress())
            ->setTo($userToNotify->getEmail())
            ->setBody($emailTemplate->renderPlainText($data));

        $this->mailer->send($message);
    }
}
