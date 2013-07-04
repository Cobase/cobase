<?php
namespace Cobase\AppBundle\Entity;

use Cobase\UserBundle\Entity\User;
use DateTime;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Cobase\AppBundle\Repository\LikeRepository")
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

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Cobase\AppBundle\Entity\Liking", mappedBy="like", fetch="EAGER")
     */
    protected $likings;

    public function __construct()
    {
        $this->setLikedAt(new DateTime());
        $this->likings = new ArrayCollection();
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

    /**
     * @param $likings
     *
     * @return Like
     */
    public function setLikings($likings)
    {
        $this->likings = $likings;

        return $this;
    }

    /**
     * @param Liking $liking
     * @return Like
     */
    public function addLiking(Liking $liking)
    {
        if (!$this->likings->contains($liking)) {
            $this->likings->add($liking);
            $liking->setLike($this);
        }

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getLikings()
    {
        return $this->likings;
    }

    /**
     * @return Liking
     */
    public function getLiking()
    {
        return count($this->likings) > 0 ? $this->likings[0] : null;
    }
}
