<?php
namespace Cobase\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\True;

/**
 * @ORM\Entity(repositoryClass="Cobase\AppBundle\Repository\HighfiveRepository")
 * @ORM\Table(name="quick_highfive")
 * @ORM\HasLifecycleCallbacks()
 */
class QuickHighfive
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Cobase\UserBundle\Entity\User", inversedBy="quickHighFives")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\Column(type="string")
     */
    protected $author;

    /**
     * @ORM\Column(type="string")
     */
    protected $comment;

    /**
     * @var
     */
    public $recaptcha;

    /**
     * @var
     */
    protected static $enableRecaptcha;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    public function __construct($enableRecaptcha)
    {
        $this->setCreated(new \DateTime());

        self::$enableRecaptcha = $enableRecaptcha;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('comment', new NotBlank(array(
            'message' => 'You must enter a comment'
        )));

        if (self::$enableRecaptcha === true) {
            $metadata->addPropertyConstraint('recaptcha', new True());
        }
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
     * Set name of the quickie sender
     *
     * @param text $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * Set name of the quickie sender
     *
     * @return text
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set comment
     *
     * @param text $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get comment
     *
     * @return text
     */
    public function getComment()
    {
        return $this->comment;
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

}