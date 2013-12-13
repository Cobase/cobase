<?php
// src/Acme/UserBundle/Entity/User.php

namespace Cobase\UserBundle\Entity;

use Cobase\AppBundle\Entity\Notification;
use Cobase\AppBundle\Entity\Like;
use Cobase\AppBundle\Entity\Post;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
     * @ORM\Column(name="avatar", type="string", nullable=true)
     */
    protected $avatar;

    /**
     * @Assert\File(maxSize="6000000")
     * @Assert\Image(
     *  minWidth = 60,
     *  minHeight = 60,
     *  groups={"Profile"}
     * )
     */
    private $avatarFile;

    /**
     * @ORM\Column(name="email_visible", type="boolean", nullable=false)
     *
     * @Assert\Type(type="bool", groups={"Profile"})
     */
    protected $emailVisible = false;

    /**
     * @ORM\OneToMany(targetEntity="Cobase\AppBundle\Entity\Group", mappedBy="user")
     */
    protected $groupsFollowed;

    /**
     * @ORM\OneToMany(targetEntity="Cobase\AppBundle\Entity\Subscription", mappedBy="user")
     */
    protected $subscriptions;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Cobase\AppBundle\Entity\Notification", mappedBy="user")
     */
    protected $notifications;

    /**
     * @ORM\OneToMany(targetEntity="Cobase\AppBundle\Entity\Post", mappedBy="user")
     */
    protected $posts;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Cobase\AppBundle\Entity\Like", mappedBy="user")
     */
    protected $likes = null;

    public function __construct()
    {
        parent::__construct();

        $this->notifications = new ArrayCollection();
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
        return !empty($this->gravatar) ? $this->gravatar : $this->email;
    }

    public function setGravatar($address)
    {
        $this->gravatar = $address;
    }

    public function hasAvatar()
    {
        return !empty($this->avatar) ? true : false;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($image)
    {
        $this->avatar = $image;
    }

    /**
     * @return UploadedFile
     */
    public function getAvatarFile()
    {
        return $this->avatarFile;
    }

    /**
     * @param UploadedFile $image
     */
    public function setAvatarFile(UploadedFile $image)
    {
        $this->avatarFile = $image;
    }

    public function getEmailVisible()
    {
        return $this->emailVisible;
    }

    public function setEmailVisible($visible)
    {
        $this->emailVisible = $visible;
    }

    /**
     * @return ArrayCollection|null
     */
    public function getGroupsFollowed()
    {
        return $this->groupsFollowed;
    }

    /**
     * @param $groupsFollowed
     * @return User
     */
    public function setGroupsFollowed($groupsFollowed)
    {
        $this->groupsFollowed = $groupsFollowed;

        return $this;
    }

    /**
     * @param mixed $subscriptions
     *
     * @return User
     */
    public function setSubscriptions($subscriptions)
    {
        $this->subscriptions = $subscriptions;

        return $this;
    }

    /**
     * @return ArrayCollection|null
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }

    /**
     * @param Like $like
     * @return User
     */
    public function addLike(Like $like)
    {
        if (!$this->getLikes()->contains($like)) {

            $this->likes->add($like);
            $like->setUser($this);
        }

        return $this;
    }

    /**
     * @param Post $post
     *
     * @return boolean
     */
    public function likesPost(Post $post)
    {
        foreach ($this->likes as $like) {
            if ($like->getResourceType() == 'post' && $like->getResourceId() == $post->getId()) {
                if ($like->getUser() === $this) {
                   return true;
                }
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function getPostLikes()
    {
        return array_filter($this->getLikes()->toArray(), function($like) {
            if ($like->getResourceType() == 'post') {
                   return $like;
            }
        });
    }

    /**
     * @param Like $like
     * @return User
     */
    public function removeLike(Like $like)
    {
        if ($this->getLikes()->contains($like)) {
            $this->likes->removeElement($like);
        }

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getLikes()
    {
        if (null == $this->likes) {
            $this->likes = new ArrayCollection();
        }

        return $this->likes;
    }

    /**
     * @param mixed $posts
     *
     * @return User
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getPosts()
    {
        return $this->posts;
    }


    public function __toString()
    {
        return $this->id;
    }

    /**
     * @param Notification $notification
     * @return Group
     */
    public function addNotification(Notification $notification)
    {
        if ($this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setUser($this);
        }

        return $this;
    }

    /**
     * @param Notification $notification
     * @return Group
     */
    public function removeNotification(Notification $notification)
    {
        if ($this->notifications->contains($notification)) {
            $this->notifications->removeElement($notification);
            $notification->setUser(null);
        }

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * Checks for an uploaded file for setting the user avatar image
     *
     * @param string $upload_dir
     */
    public function saveUploadedAvatar($upload_dir)
    {
        $uploaded = $this->getAvatarFile();

        if (null === $uploaded) {
            return;
        }

        $filename = md5($this->email) . '.' . $uploaded->getClientOriginalExtension();

        $this->getAvatarFile()->move(
            $upload_dir,
            $filename
        );

        $this->avatar = $filename;

        $this->avatarFile = null;
    }
}