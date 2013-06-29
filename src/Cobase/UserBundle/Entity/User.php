<?php
// src/Acme/UserBundle/Entity/User.php

namespace Cobase\UserBundle\Entity;

use Cobase\AppBundle\Entity\Like;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Cobase\AppBundle\Repository\UserRepository")
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="Please enter your real name.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *  min="5",
     *  max="255",
     *  minMessage="The name is too short.",
     *  maxMessage="The name is too long.",
     *  groups={"Registration", "Profile"}
     * )
     */
    protected $name;
    
    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     *
     * @Assert\Length(
     *  max="150",
     *  maxMessage="The gravatar email is too long.", groups={"Registration", "Profile"}
     * )
     */
    protected $gravatar;
    
    /**
     * @ORM\OneToMany(targetEntity="Cobase\AppBundle\Entity\Group", mappedBy="user")
     */
    protected $groupsFollowed;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Cobase\AppBundle\Entity\Like", mappedBy="Like")
     */
    protected $likes;

    public function __construct()
    {
        parent::__construct();

        $this->likes = new ArrayCollection();
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function getGravatar()
    {
        return $this->gravatar;
    }
    
    public function setGravatar($address)
    {
        $this->gravatar = $address;
    }

    public function getGroupsFollowed()
    {
        return $this->groupsFollowed;
    }

    public function addLike(Like $like)
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setCreator($this);
        }

        return $this;
    }

    /**
     * @param Like $like
     * @return User
     */
    public function removeLike(Like $like)
    {
        if ($this->likes->contains($like)) {
            $this->likes->removeElement($like);
        }

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getLikes()
    {
        return $this->likes;
    }

    public function __toString() 
    {
        return $this->id;
    }
}