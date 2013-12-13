<?php
namespace Cobase\AppBundle\Entity;

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

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var DateTime
     *
     * @Gedmo\Mapping\Annotation\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @var DateTime
     *
     * @Gedmo\Mapping\Annotation\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $updated;

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
     * @param DateTime $created
     *
     * @return $this
     */
    public function setCreated(DateTime $created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param DateTime $updated
     *
     * @return $this
     */
    public function setUpdated(DateTime $updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
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
