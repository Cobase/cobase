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
     * @var Group
     * @ORM\ManyToOne(targetEntity="Cobase\AppBundle\Entity\Group")
     */
    protected $group;

    /**
     * @param Group $group
     */
    public function __construct(Group $group)
    {
        $this->group = $group;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Group $group
     *
     * @return PostEvent
     */
    public function setGroup(Group $group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @return Group
     */
    public function getGroup()
    {
        return $this->group;
    }
}
