<?php
namespace Cobase\AppBundle\Entity;

use Cobase\UserBundle\Entity\User;
use Cobase\AppBundle\Entity\Group;

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
     * @var User
     * @ORM\ManyToOne(targetEntity="Cobase\UserBundle\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\Column(type="string", length=1250)
     */
    protected $content;

    /**
     * @var Group
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="posts")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     */
    protected $group;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $updated;
    
    /**
     * @var ArrayCollection
     */
    protected $likes;

    /**
     * @var integer
     */
    protected $maxFeedTitleLength = 400;

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
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param User $user
     *
     * @return Post
     */
    public function setUser(User $user)
    {
        $this->user = $user;

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
     * @param text $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
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
     * @param Group $group
     */
    public function setGroup(Group $group)
    {
        $this->group = $group;
    }

    /**
     * Get group
     *
     * @return Group
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
     * This method returns feed item title. The title is shortened if required.
     *
     * You can set the maximum length of the title using setMaxFeedTitleLength().
     *
     * @return string
     */
    public function getFeedItemTitle()
    {
        $content = trim($this->getContent());

        if (mb_strlen($content) <= $this->getMaxFeedTitleLength()) {
           return $content . " (" . $this->getUser()->getName() . ")";
        }

        $content = mb_substr($content, 0, $this->getMaxFeedTitleLength() + 1);
        $lastSpacePos = strrpos($content, ' ');

        if ($lastSpacePos !== false) {

            $content = mb_substr($content, 0, $lastSpacePos);
        }

        return trim($content) . "... (" . $this->getUser()->getName() . ")";
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

    /**
     * @param int $maxFeedTitleLength
     *
     * @return Post
     */
    public function setMaxFeedTitleLength($maxFeedTitleLength)
    {
        $this->maxFeedTitleLength = $maxFeedTitleLength;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxFeedTitleLength()
    {
        return $this->maxFeedTitleLength;
    }
}