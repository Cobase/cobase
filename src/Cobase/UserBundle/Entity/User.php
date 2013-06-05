<?php
// src/Acme/UserBundle/Entity/User.php

namespace Cobase\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
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
     * @Assert\MinLength(limit="5", message="The name is too short.", groups={"Registration", "Profile"})
     * @Assert\MaxLength(limit="255", message="The name is too long.", groups={"Registration", "Profile"})
     */
    protected $name;
    
    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     *
     * @Assert\MaxLength(limit="150", message="The gravatar email is too long.", groups={"Registration", "Profile"})
     */
    protected $gravatar;
    
    /**
     * @ORM\OneToMany(targetEntity="Cobase\AppBundle\Entity\Event", mappedBy="user")
     */
    protected $events;

    /**
     * @ORM\OneToMany(targetEntity="Cobase\AppBundle\Entity\QuickHighfive", mappedBy="user")
     */
    protected $quickHighFives;


    public function __construct()
    {
        parent::__construct();
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

    public function getHighfives()
    {
        return $this->highFives;
    }

    public function getEvents()
    {
        return $this->events;
    }

    public function getQuickHighfives()
    {
        return $this->quickHighFives;
    }

    public function __toString() 
    {
        return $this->id;
    }
}