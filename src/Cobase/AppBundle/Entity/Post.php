<?php
namespace Cobase\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Eko\FeedBundle\Item\Writer\RoutedItemInterface;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Cobase\AppBundle\Repository\PostRepository")
 * @ORM\Table(name="posts")
 * @ORM\HasLifecycleCallbacks()
 */
class Post implements Likeable, RoutedItemInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Cobase\UserBundle\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\Column(type="string", length=1250)
     */
    protected $content;

    /**
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="posts")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     */
    protected $group;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updated;
    
    /**
     * @var ArrayCollection
     */
    protected $likes;

    public function __construct()
    {
        $this->setCreated(new \DateTime());

        $this->setUpdated(new \DateTime());

        $this->likes = new ArrayCollection();
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('content', new NotBlank(array(
            'message' => 'You must enter a content'
        )));
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param string $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set comment
     *
     * @param text $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     * 
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set created
     *
     * @param datetime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * Get created
     *
     * @return datetime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param datetime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * Get updated
     *
     * @return datetime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set group
     *
     * @param Cobase\AppBundle\Entity\Group $group
     */
    public function setGroup(\Cobase\AppBundle\Entity\Group $group)
    {
        $this->group = $group;
    }

    /**
     * Get group
     *
     * @return Cobase\AppBundle\Entity\Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @return string
     */
    public function getLikeableType()
    {
        return 'post';
    }

    /**
     * @return mixed
     */
    public function getLikeableId()
    {
        return $this->getId();
    }

    /**
     * @param ArrayCollection $likes
     *
     * @return Post
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @return Post
     */
    public function removeLikes()
    {
        foreach($this->getLikes() as $likea)
        {
            //$tag->removeBook($this);
            if ($this->getLikes()->contains($likea)) {
                $this->getLikes()->removeElement($likea);
            }
        }

        return $this;
    }

    /**
     * This method returns feed item title
     *
     *
     * @return string
     */
    public function getFeedItemTitle()
    {
        $content = $this->getContent();

        // @TODO replace naive substring with proper logic
        return substr($content, 0, 250);
    }

    /**
     * This method returns feed item description (or content)
     *
     *
     * @return string
     */
    public function getFeedItemDescription()
    {
        return $this->getContent();
    }

    /**
     * This method returns the name of the route
     *
     *
     * @return string
     */
    public function getFeedItemRouteName()
    {
        return 'CobaseAppBundle_post_view';
    }

    /**
     * This method returns the parameters for the route.
     *
     *
     * @return array
     */
    public function getFeedItemRouteParameters()
    {
        return array('postId' => $this->getId());
    }

    /**
     * This method returns the anchor to be appended on this item's url
     *
     *
     * @return string The anchor, without the "#"
     */
    public function getFeedItemUrlAnchor()
    {
        return '';
    }

    /**
     * This method returns item publication date
     *
     *
     * @return \DateTime
     */
    public function getFeedItemPubDate()
    {
        return $this->getCreated();
    }
}