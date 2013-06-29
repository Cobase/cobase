<?php

namespace Cobase\AppBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\True;

class Enquiry
{
    protected $name;

    protected $email;

    protected $subject;

    protected $body;

    public $recaptcha;

    protected static $enableRecaptcha;

    public function __construct($enableRecaptcha) {
        self::$enableRecaptcha = $enableRecaptcha;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('name', new Assert\NotBlank());

        $metadata->addPropertyConstraint('email', new Assert\Email());

        $metadata->addPropertyConstraint('subject', new Assert\NotBlank());
        $metadata->addPropertyConstraint('subject', new Assert\Length(array('max' => 150)));

        $metadata->addPropertyConstraint('body', new Assert\Length(array('min' => 50)));

        if (self::$enableRecaptcha === true) {
            $metadata->addPropertyConstraint('recaptcha', new True());
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }
}