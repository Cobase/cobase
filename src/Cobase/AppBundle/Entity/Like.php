<?php
namespace Cobase\AppBundle\Entity;

use Cobase\UserBundle\Entity\User;
use DateTime;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity()
 * @ORM\Table(name="likes")
 */
class Like 
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="liked_at", type="datetime")
     */
    protected $likedAt;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Cobase\UserBundle\Entity\User", inversedBy="likes")
     */
    protected $user;

    public function __construct()
    {
        $this->setLikedAt(new DateTime());
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param DateTime $likedAt
     *
     * @return Like
     */
    public function setLikedAt($likedAt)
    {
        $this->likedAt = $likedAt;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getLikedAt()
    {
        return $this->likedAt;
    }

    /**
     * @param User $user
     *
     * @return Like
     */
    public function setUser(User $user)
    {
        if ($this->user !== $user) {
            $this->user = $user;
            $user->addLike($this);
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
