<?php

namespace Cobase\AppBundle\Controller;

use Cobase\AppBundle\Controller\BaseController;
use Cobase\AppBundle\Entity\Event;
use Cobase\AppBundle\Form\EventType;
use Cobase\AppBundle\Entity\Highfive;
use Cobase\AppBundle\Form\HighfiveType;

/**
 * Event controller.
 */
class EventController extends BaseController
{
    /**
     * Render a new event form
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction()
    {
        $event = new Event();
        $user  = $this->getCurrentUser();
        $form = $this->createForm(new EventType(), $event);
        $service = $this->getEventService();

        $latestEvents = $service->getLatestPublicEvents($this->container->getParameter('cobase_app.comments.max_latest_events'));

        $gravatarGiven = true;
        if ($user->getGravatar() == null) {
            // @TODO: Implement this feature
            //$gravatarGiven = false;
        }

        return $this->render('CobaseAppBundle:Event:create.html.twig', array(
            'form'     => $form->createView(),
            'gravatar' => $gravatarGiven,
            'latestEvents' => $latestEvents,
        ));
    }

    /**
     * Create new event
     *
     * This function handles the new event's form data and creates new event
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction()
    {
        $event   = new Event();
        $form    = $this->createForm(new EventType(), $event);
        $user    = $this->getCurrentUser();
        $service = $this->getEventService();

        $latestEvents = $service->getLatestPublicEvents($this->container->getParameter('cobase_app.comments.max_latest_events'));

        if ($this->getRequest()->getMethod() != 'POST') {
            return $this->redirect($this->generateUrl('CobaseAppBundle_event_new'));
        }

        if (!$this->processForm($form)) {
            return $this->render('CobaseAppBundle:Event:create.html.twig', array(
                'form' => $form->createView(),
                'latestEvents' => $latestEvents,
            ));
        }

        $eventUrl = $this->container->get('router')
            ->generate('CobaseAppBundle_event_view', array('eventId' => $event->getShortUrl()), true);

        $service->saveEvent($event, $user);

        if ($event->getIsPublic()) {
            return $this->redirect($this->generateUrl('CobaseAppBundle_homepage'));
        } else {
            return $this->render('CobaseAppBundle:Event:unlisted-info.html.twig', array(
                'event'        => $event,
                'eventUrl'     => $eventUrl,
                'latestEvents' => $latestEvents,
            ));
        }
    }

    /**
     * View Event
     *
     * @param $eventId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction($eventId)
    {
        $highfive = new Highfive();

        $eventService    = $this->getEventService();
        $highfiveService = $this->getHighfiveService();

        $request  = $this->getRequest();
        $form     = $this->createForm(new HighfiveType(), $highfive);
        $user     = $this->getCurrentUser();
        $event    = $eventService->getEventById($eventId);

        if (!$event) {
            return $this->render('CobaseAppBundle:Event:notfound.html.twig', array());
        }

        $latestEvents = $eventService->getLatestPublicEvents($this->container->getParameter('cobase_app.comments.max_latest_events'));

        $submitted     = false;
        $showForm      = true;
        $allowModify   = false;

        if ($user) {
            if ($highfiveService->hasUserSubmittedHighfiveForEvent($event, $user)) {
                $submitted = true;
                $showForm = false;
            }
            if ($event->getUser() === $user) {
                $allowModify = true;
                $showForm = false;
            }
        } else {
            $showForm = false;
        }

        if ($request->getMethod() == 'POST' && !$submitted && $showForm) {
            if (!$this->processForm($form)) {
                $showForm = true;
            } else {
                $highfiveService->saveHighfive($highfive, $event, $user);

                $this->sendMail("You have received new high five for an event",
                                $event->getUser()->getEmail(),
                                $this->renderView('CobaseAppBundle:Page:newHighFiveEmail.txt.twig',
                                    array('event' => $event)
                                ));

                $this->get('session')->getFlashBag()->add('highfive.saved', 'Your High Five has been sent, thank you!');

                return $this->forward('CobaseAppBundle:Event:view', array(
                    'eventId' => $eventId,
                ));
            }
        }

        return $this->render('CobaseAppBundle:Event:view.html.twig', array(
            'event'         => $event,
            'latestEvents'  => $latestEvents,
            'form'          => $form->createView(),
            'showForm'      => $showForm,
            'allowModify'   => $allowModify,
        ));
    }

    /**
     * Modify Event
     *
     * @param $eventId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function modifyAction($eventId)
    {
        $eventService    = $this->getEventService();
        $highfiveService = $this->getHighfiveService();

        $event    = $eventService->getEventById($eventId);
        $request  = $this->getRequest();
        $user     = $this->getCurrentUser();

        $latestEvents = $eventService->getLatestPublicEvents($this->container->getParameter('cobase_app.comments.max_latest_events'));

        if (!$event) {
            return $this->render('CobaseAppBundle:Event:notfound.html.twig', array('latestEvents' => $latestEvents));
        }

        if ($event->getUser() !== $user) {
            return $this->render('CobaseAppBundle:Event:noaccess.html.twig', array('latestEvents' => $latestEvents));
        }

        $form = $this->createForm(new EventType(), $event);

        if ($request->getMethod() == 'POST') {
            if ($this->processForm($form)) {
                $eventService->saveEvent($event, $user);

                $this->get('session')->getFlashBag()->add('event.saved', 'Your changes have been saved!');

                return $this->redirect($this->generateUrl('CobaseAppBundle_event_view', array(
                    'eventId' => $eventId,
                )));
            }
        }

        return $this->render('CobaseAppBundle:Event:modify.html.twig', array(
            'event'         => $event,
            'latestEvents'  => $latestEvents,
            'form'          => $form->createView(),
        ));
    }

    /**
     * Search for Events
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchAction()
    {
        $searchWord = $this->getRequest()->get('searchWord');
        $eventService = $this->getEventService();
        $events       = $eventService->findAllBySearchWord($searchWord);

        return $this->render('CobaseAppBundle:Event:searchResults.html.twig', array(
            'events'    => $events,
        ));
    }

    /**
     * Show all events
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAllAction($orderByType = 'b.title', $orderType = 'asc')
    {
        $orderBy = 'b.title';
        if ($orderByType == 'created') {
            $orderBy = 'b.created';
        }

        $order = 'asc';
        if ($orderType == 'desc') {
            $order = 'desc';
        }

        $limit = null;

        $eventService = $this->getEventService();
        $events = $eventService->getAllPublicEvents($limit, $orderBy, $order);

        return $this->render('CobaseAppBundle:Page:allEvents.html.twig', array(
            'events'    => $events
        ));
    }

}