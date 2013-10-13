<?php
namespace Cobase\AppBundle\Entity;

use Cobase\Component\Doctrine\Traits\TimestampTrait;
use Cobase\UserBundle\Entity\User;
use Cobase\AppBundle\Entity\Group;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Cobase\AppBundle\Repository\NotificationRepository")
 * @ORM\Table(name="notifications")
 */
class Notification
{
    use TimestampTrait;

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @param User $user
     * @param Group $group
     */
    public function __construct(User $user, Group $group)
    {
        $this->setUser($user);
        $this->setGroup($group);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="Cobase\UserBundle\Entity\User", inversedBy="notifications")
     */
    protected $user;

    /**
     * @var Group
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="notifications")
     */
    protected $group;

    /**
     * @param Group $group
     *
     * @return Notification
     */
    public function setGroup(Group $group)
    {
        if ($this->group !== $group) {
            $this->group = $group;
            $this->group->addNotification($this);
        }

        return $this;
    }

    /**
     * @return Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param User $user
     *
     * @return Notification
     */
    public function setUser(User $user)
    {
        if ($this->user !== $user) {
            $this->user = $user;
            $this->user->addNotification($this);
        }

        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

}
