<?php
namespace Cobase\AppBundle\Entity;

use Cobase\AppBundle\Entity\Likeable;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="likings")
 * @ORM\Entity()
 */
class Liking 
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Like
     * @ORM\ManyToOne(targetEntity="CoBase\AppBundle\Entity\Like", inversedBy="liking")
     * @ORM\JoinColumn(name="like_id", referencedColumnName="id")
     */
    protected $like;

    /**
     * @var string
     *
     * @ORM\Column(name="resource_id", type="string", length=50)
     */
    protected $resourceId;

    /**
     * @var string
     *
     * @ORM\Column(name="resource_type", type="string", length=50)
     */
    protected $resourceType;

    public function __construct(Like $like = null, Likeable $resource = null)
    {
        if (null !== $like) {
            $this->setLike($like);
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Like $like
     *
     * @return Liking
     */
    public function setLike(Like $like)
    {
        $this->like = $like;

        return $this;
    }

    /**
     * @return Like
     */
    public function getLike()
    {
        return $this->like;
    }

    /**
     * @param Likeable $resource
     *
     * @return Liking
     */
    public function setResource(Likeable $resource)
    {
        $this->resourceId = $resource->getLikeableId();
        $this->resourceType = $resource->getLikeableType();

        return $this;
    }

    /**
     * @return string
     */
    public function getResourceId()
    {
        return $this->resourceId;
    }

    /**
     * @return string
     */
    public function getResourceType()
    {
        return $this->resourceType;
    }
}
