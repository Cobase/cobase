<?php

namespace Cobase\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;

/**
 * Base controller.
 *
 * Place all common functions here available across all controllers
 */
class BaseController extends Controller
{
    /**
     * @param Form $form
     * @return boolean
     */
    public function processForm(Form $form)
    {
        $form->bind($this->getRequest());
        return $form->isValid();
     }

    /**
     * Return current user's entity or null if not logged in
     *
     * @return null|App/UserBundle/Entity/User
     */
    public function getCurrentUser() {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if ($user === 'anon.') {
            return null;
        }
        return $user;
    }

    /**
     * Return given user's entity or null if not logged in
     *
     * @return null|App/UserBundle/Entity/User
     */
    public function getUserByUsername($username) {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if ($user === 'anon.') {
            return null;
        }
        return $user;
    }

    /**
     * @return GroupService
     */
    public function getGroupService()
    {
        return $this->container->get('cobase_app.service.group');
    }

    /**
     * @return PostService
     */
    public function getPostService()
    {
        return $this->container->get('cobase_app.service.post');
    }

    /**
     * @return SubscriptionService
     */
    public function getSubscriptionService()
    {
        return $this->container->get('cobase_app.service.subscription');
    }

    /**
     * @return UserService
     */
    public function getUserService()
    {
        return $this->container->get('cobase_app.service.user');
    }

    /**
     * Send email
     *
     * @param $subject
     * @param $emailFrom
     * @param $emailTo
     * @param $message
     */
    public function sendMail($subject, $emailTo, $message)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($this->container->getParameter('cobase_app.emails.contact_email'))
            ->setTo($emailTo)
            ->setBody($message);

        $this->get('mailer')->send($message);
    }

    /**
     * Merge page related variables with common variables
     * 
     * @param array pageVariables
     */
    public function mergeVariables(array $pageVariables)
    {
       return array_merge($pageVariables, $this->getCommonVariables()); 
    }

    /**
     * Get array of variables
     * 
     * @return array
     */
    private function getCommonVariables()
    {
        return array(
            'subscriptions' => $this->getSubscriptions(),
        ); 
    }

    /**
     * Get group subscriptions for current user
     * 
     * @return array
     */
    public function getSubscriptions()
    {
        $user  = $this->getCurrentUser();
        $subscriptionService = $this->getSubscriptionService();
        $subscriptions = array();
            
        if ($user) {
            $subscriptions = $subscriptionService->getSubscriptionsForUser($user);
        }
        
        return $subscriptions;
    }

}