<?php

namespace Cobase\AppBundle\Controller;

use Cobase\AppBundle\Controller\BaseController;
use Cobase\AppBundle\Entity\Enquiry;
use Cobase\AppBundle\Form\EnquiryType;

class PageController extends BaseController
{
    public function indexAction()
    {
        $user  = $this->getCurrentUser();
        $posts = array();

        $groupService = $this->getGroupService();
        $maxLatestGroupAmount = $this->container->getParameter('cobase_app.comments.max_latest_groups');
        $groups = $groupService->getLatestPublicGroups($maxLatestGroupAmount);

        //$events       = $eventService->findAllBySearchWord('Second');

        //$highfiveService = $this->getHighfiveService();
        //$maxHighfivesAmount = $this->container->getParameter('cobase_app.comments.max_latest_highfives');
        //$highfives = $highfiveService->getLatestHighfivesforPublicEvents($maxHighfivesAmount);

        return $this->render('CobaseAppBundle:Page:index.html.twig', array(
            'groups'    => $groups,
        ));
    }

    public function highFivesReceivedAction()
    {
        $user  = $this->getCurrentUser();
        $highfives = array();

        $highfiveService = $this->getHighfiveService();
        $highfives = $highfiveService->getHighfivesForUser($user);

        $eventService = $this->getEventService();
        $latestEvents = $eventService->getLatestPublicEvents($this->container->getParameter('cobase_app.comments.max_latest_events'));

        return $this->render('CobaseAppBundle:Page:latestForYou.html.twig', array(
            'highfives' => $highfives,
            'latestEvents' => $latestEvents,
            'quickHighfives' => $user->getQuickHighfives(),
        ));
    }

    public function aboutAction()
    {
        return $this->render('CobaseAppBundle:Page:about.html.twig');
    }
    
    public function contactAction()
    {
        $currentUser  = $this->getCurrentUser();

        $enableCaptcha = true;
        if ($currentUser) {
            $enableCaptcha = false;
        } else {
            if (!$this->container->getParameter('cobase_app.enable_recaptha')) {
                $enableCaptcha = false;
            }
        }

        $enquiry = new Enquiry($enableCaptcha);

        $form = $this->createForm(new EnquiryType(), $enquiry);

        if ($enableCaptcha === false) {
            $form->remove('recaptcha');
        }

        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
    
            if ($form->isValid()) {
                $message = \Swift_Message::newInstance()
                    ->setSubject('Contact enquiry from the app')
                    ->setFrom('info@arturgajewski.com')
                    ->setTo($this->container->getParameter('cobase_app.emails.contact_email'))
                    ->setBody($this->renderView('CobaseAppBundle:Page:contactEmail.txt.twig', array('enquiry' => $enquiry)));
                $this->get('mailer')->send($message);
        
                $this->get('session')->setFlash('app-notice', 'Your contact enquiry was successfully sent. Thank you!');
        
                // Redirect - This is important to prevent users re-posting
                // the form if they refresh the page
                return $this->redirect($this->generateUrl('CobaseAppBundle_contact'));
            }
        }
    
        return $this->render('CobaseAppBundle:Page:contact.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function sidebarAction()
    {
        $em = $this->getDoctrine()
                   ->getManager();
    
        $tags = $em->getRepository('CobaseAppBundle:Entry')
                   ->getTags();
    
        $tagWeights = $em->getRepository('CobaseAppBundle:Entry')
                         ->getTagWeights($tags);
    
        $commentLimit   = $this->container
                               ->getParameter('cobase_app.comments.latest_comment_limit');
        $latestComments = $em->getRepository('CobaseAppBundle:Comment')
                             ->getLatestComments($commentLimit);
    
        return $this->render('CobaseAppBundle:Page:sidebar.html.twig', array(
            'latestComments'    => $latestComments,
            'tags'              => $tagWeights
        ));
    }



}
