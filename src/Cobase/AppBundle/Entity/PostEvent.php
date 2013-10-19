<?php
namespace Cobase\AppBundle\Entity;

use Cobase\Component\Doctrine\Traits\TimestampTrait;
use Cobase\AppBundle\Entity\Group;

use DateTime;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="postevents")
 */
class PostEvent
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
     * @var Post
     * @ORM\ManyToOne(targetEntity="Cobase\AppBundle\Entity\Post")
     */
    protected $post;

    /**
     * @var DateTime
     *
     * @Gedmo\Mapping\Annotation\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post     = $post;
    }

    /**
     * @var DateTime
     *
     * @Gedmo\Mapping\Annotation\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $updated;

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
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Group
     */
    public function getGroup()
    {
        return $this->post->getGroup();
    }

    /**
     * @param Post $post
     *
     * @return PostEvent
     */
    public function setPost(Post $post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
    }
}

