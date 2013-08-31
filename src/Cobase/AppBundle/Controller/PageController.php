<?php

namespace Cobase\AppBundle\Controller;

use Cobase\AppBundle\Controller\BaseController;

class PageController extends BaseController
{
    public function indexAction()
    {
        if ($redirect = $this->checkLoginRequirement('CobaseAppBundle_all_groups')) {
            return $redirect;
        }
        
        $user  = $this->getCurrentUser();

        $subscriptionService = $this->getSubscriptionService();
        $posts = $subscriptionService->findAllSubscribedPostsForUser($user);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $posts,
            $this->get('request')->query->get('page', 1) /*page number*/,
            $this->container->getParameter('subscriptions_per_page') /*limit per page*/
        );

        $groupService = $this->getGroupService();
        $groups = $groupService->getGroups();
        
        return $this->render('CobaseAppBundle:Page:index.html.twig', 
            $this->mergeVariables(
                array(
                    'pagination' => $pagination,
                    'groups'     => $groups,
                    'posts'      => $posts,
                )
            )
        );
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
            $form->submit($request);
    
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
    
        return $this->render('CobaseAppBundle:Page:contact.html.twig',
            $this->mergeVariables(
                array(
                    'form' => $form->createView()
                )
            )
        );
    }

}
