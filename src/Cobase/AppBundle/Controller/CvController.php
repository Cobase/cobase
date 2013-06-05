<?php

namespace Cobase\AppBundle\Controller;

use Cobase\AppBundle\Controller\BaseController;
use Cobase\AppBundle\Entity\Event;
use Cobase\AppBundle\Form\EventType;
use Cobase\AppBundle\Entity\Highfive;
use Cobase\AppBundle\Form\HighfiveType;
use Cobase\AppBundle\Entity\QuickHighfive;
use Cobase\AppBundle\Form\QuickHighfiveType;

/**
 * Curriculum Vitae controller.
 */
class CvController extends BaseController
{
    /**
     * View CV
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction()
    {
        $user     = $this->getCurrentUser();
        $service  = $this->getEventService();
        $events   = array();

        if ($user) {
            $events = $service->getEventsForCurrentUser();
        }

        return $this->render('CobaseAppBundle:Cv:view.html.twig', array(
            'user'   => $user,
            'events' => $events,
        ));
    }

    /**
     * View CV for a given user
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userAction($username)
    {
        $service     = $this->getEventService();
        $userManager = $this->get('fos_user.user_manager');
        $events      = array();

        $user        = $userManager->findUserBy(array('username' => $username));

        if ($user) {
            $events = $service->getEventsForUser($user);
        }

        return $this->render('CobaseAppBundle:Cv:view.html.twig', array(
            'user'   => $user,
            'events' => $events,
        ));
    }

}