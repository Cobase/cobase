<?php

namespace Cobase\AppBundle\Controller;

use Cobase\AppBundle\Service\GroupService;
use Cobase\AppBundle\Service\LikeService;
use Cobase\AppBundle\Service\PostService;
use Cobase\AppBundle\Service\SubscriptionService;
use Cobase\AppBundle\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;

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
        $form->submit($this->getRequest());
        return $form->isValid();
     }

    /**
     * Return current user's entity or null if not logged in
     *
     * @return null|App/UserBundle/Entity/User
     */
    public function getCurrentUser() {
        return $this->getUserService()->getCurrentUser();
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
     * @return LikeService
     */
    public function getLikeService()
    {
        return $this->container->get('cobase_app.service.like');
    }

    /**
     * @return TwitterService
     */
    public function getTwitterService()
    {
        return $this->container->get('cobase_app.service.twitter');
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
    public function mergeVariables(array $pageVariables = null)
    {
        if ($pageVariables) {
            return array_merge($pageVariables, $this->getCommonVariables());    
        }
        return $this->getCommonVariables();
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
            'latestGroups'  => $this->getGroupService()->getLatestPublicGroups(10),
            'latestUsers'   => $this->getUserService()->getLatestUsers(10),
            'statistics'    => $this->getStatistics(),
        ); 
    }

    /**
     * @return array
     */
    public function getStatistics()
    {
        $users = $this->getUserService()->getUsers();
        $groups = $this->getGroupService()->getGroups();
        $posts = $this->getPostService()->getPosts();

        return array(
            'users'  => $users,
            'groups' => $groups,
            'posts'  => $posts,
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

    /**
     * @param array $data
     *
     * @return JsonResponse
     */
    public function getJsonResponse(array $data = array() )
    {
        return new JsonResponse($data);
    }

    /**
     * @param mixed $response
     * @return array
     */
    public function createJsonSuccessResponse($response = null)
    {
        return $this->createResponseFor('success', $response);
    }

    /**
     * @param mixed $response
     * @return array
     */
    public function createJsonFailureResponse($response = null)
    {
        return $this->createResponseFor('failure', $response);
    }

    /**
     * Check if login is required and if user is logged in and redirect accordingly
     * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function checkLoginRequirement($routeName, $doRedirect = true)
    {
        // Check if login is required
        if ($this->container->getParameter('login_required')) {
            // Check if user is logged in
            if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                // Forward to login page
                return $this->redirect($this->generateUrl('fos_user_security_login'));
            }
        } else {
            if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                if ($doRedirect) {
                    // Forward to given route based on route name
                    return $this->redirect($this->generateUrl($routeName));    
                }
            }
        }
        return null;
    }
    
    /**
     * @param string $what
     * @param mixed $response
     * @return array
     */
    private function createResponseFor($what, $response = null)
    {
        return new JsonResponse(array(
            $what => $response === null ? true : $response,
        ) );
    }
}