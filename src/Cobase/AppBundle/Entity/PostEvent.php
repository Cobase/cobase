<?php
namespace Cobase\AppBundle\Entity;

use Cobase\Component\Doctrine\Traits\TimestampTrait;
use Cobase\AppBundle\Entity\Group;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="postevents")
 */
class PostEvent
{
    use TimestampTrait;

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
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post     = $post;
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

