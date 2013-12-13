<?php
namespace Cobase\AppBundle\Service;

use \Swift_Mailer;

class EmailService
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @param Swift_Mailer $mailer
     */
    public function __construct(Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Send email
     *
     * @param $subject
     * @param $from
     * @param $emailFrom
     * @param $emailTo
     * @param $message
     */
    public function sendMail($subject, $from, $emailTo, $message)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($emailTo)
            ->setBody($message);

        $this->mailer->send($message);
    }
}
