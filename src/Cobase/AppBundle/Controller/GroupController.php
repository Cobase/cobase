<?php

namespace Cobase\AppBundle\Controller;

use Cobase\AppBundle\Controller\BaseController;
use Cobase\AppBundle\Entity\Group;
use Cobase\AppBundle\Form\GroupType;
use Cobase\AppBundle\Entity\Post;
use Cobase\AppBundle\Form\PostType;

use Symfony\Component\HttpFoundation\Response;

/**
 * Group controller.
 */
class GroupController extends BaseController
{
    /**
     * Render a new group form
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction()
    {
        $group = new Group();
        $user  = $this->getCurrentUser();
        $form = $this->createForm(new GroupType(), $group);
        $service = $this->getGroupService();

        $latestGroups = $service->getLatestPublicGroups($this->container->getParameter('cobase_app.comments.max_latest_groups'));

        $gravatarGiven = true;
        if ($user->getGravatar() == null) {
            // @TODO: Implement this feature
            $gravatarGiven = false;
        }

        return $this->render('CobaseAppBundle:Group:create.html.twig',
            $this->mergeVariables(
                array(
                    'form'     => $form->createView(),
                    'gravatar' => $gravatarGiven,
                    'latestGroups' => $latestGroups,
                )
            )
        );
    }

    /**
     * Create new group
     *
     * This function handles the new group's form data and creates new group
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction()
    {
        $group   = new Group();
        $form    = $this->createForm(new GroupType(), $group);
        $user    = $this->getCurrentUser();
        $service = $this->getGroupService();

        $latestGroups = $service->getLatestPublicGroups($this->container->getParameter('cobase_app.comments.max_latest_groups'));

        if ($this->getRequest()->getMethod() != 'POST') {
            return $this->redirect($this->generateUrl('CobaseAppBundle_group_new'));
        }

        if (!$this->processForm($form)) {
            return $this->render('CobaseAppBundle:Group:create.html.twig', 
                $this->mergeVariables(
                    array(
                        'form' => $form->createView(),
                        'latestGroups' => $latestGroups,
                    )
                )
            );
        }

        $groupUrl = $this->container->get('router')
            ->generate('CobaseAppBundle_group_view', array('groupId' => $group->getShortUrl()), true);

        $service->saveGroup($group, $user);
  
        if ($group->getIsPublic()) {
            return $this->redirect($this->generateUrl('CobaseAppBundle_homepage'));
        } else {
            return $this->render('CobaseAppBundle:Group:unlisted-info.html.twig',
                $this->mergeVariables(
                    array(
                        'group'        => $group,
                        'groupUrl'     => $groupUrl,
                        'latestGroups' => $latestGroups,
                    )
                )
            );
        }
    }

    /**
     * View Group
     *
     * @param $groupId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction($groupId)
    {
        $post = new Post();
        
        $groupService    = $this->getGroupService();
        $postService = $this->getPostService();
        $subscriptionService = $this->getSubscriptionService();
        
        $request  = $this->getRequest();
        $form     = $this->createForm(new PostType(), $post);
        $user     = $this->getCurrentUser();
        $group    = $groupService->getGroupById($groupId);

        $isSubscribed = $subscriptionService->hasUserSubscribedToGroup($group, $user);
        
        if (!$group) {
            return $this->render('CobaseAppBundle:Group:notfound.html.twig', array());
        }

        $allowModify   = false;

        if ($user) {
            if ($group->getUser() === $user) {
                $allowModify = true;
            }
        }
        if ($request->getMethod() == 'POST') {
            if ($this->processForm($form)) {
                $postService->savePost($post, $group, $user);

                #$this->sendMail("You have received new high five for an event",
                #    $group->getUser()->getEmail(),
                #    $this->renderView('PortalAppBundle:Page:newHighFiveEmail.txt.twig',
                #        array('event' => $event)
                #    ));

                $this->get('session')->getFlashBag()->add('post.saved', 'Your post has been sent, thank you!');

                return $this->redirect($this->generateUrl('CobaseAppBundle_group_view', 
                    $this->mergeVariables(
                        array(
                            'groupId' => $groupId,
                        )
                    )
                ));
               
            }
        }
        
        return $this->render('CobaseAppBundle:Group:view.html.twig',
            $this->mergeVariables(
                array(
                    'group'         => $group,
                    'form'          => $form->createView(),
                    'subscribed'    => $isSubscribed,
                    'allowModify'   => $allowModify,
                )
            )
        );
    }

    /**
     * Modify Group
     *
     * @param $groupId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function modifyAction($groupId)
    {
        $groupService    = $this->getGroupService();
        $postService = $this->getPostService();

        $group    = $groupService->getGroupById($groupId);
        $request  = $this->getRequest();
        $user     = $this->getCurrentUser();

        $latestGroups = $groupService->getLatestPublicGroups($this->container->getParameter('cobase_app.comments.max_latest_groups'));

        if (!$group) {
            return $this->render('CobaseAppBundle:Group:notfound.html.twig',
                $this->mergeVariables(
                    array('latestGroups' => $latestGroups)
                )
            );
        }

        if ($group->getUser() !== $user) {
            return $this->render('CobaseAppBundle:Group:noaccess.html.twig',
                $this->mergeVariables(
                    array('latestGroups' => $latestGroups)
                )
            );
        }

        $form = $this->createForm(new GroupType(), $group);

        if ($request->getMethod() == 'POST') {
            if ($this->processForm($form)) {
                $groupService->saveGroup($group, $user);

                $this->get('session')->getFlashBag()->add('group.saved', 'Your changes have been saved!');

                return $this->redirect($this->generateUrl('CobaseAppBundle_group_view',
                    $this->mergeVariables(
                        array(
                            'groupId' => $groupId,
                        )
                    )
                ));
            }
        }

        return $this->render('CobaseAppBundle:Group:modify.html.twig', 
            $this->mergeVariables(
                array(
                    'group'         => $group,
                    'latestGroups'  => $latestGroups,
                    'form'          => $form->createView(),
                )
            )
        );
    }

    /**
     * Subscribe a user to a group
     * 
     * @param $groupId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function subscribeAction($groupId)
    {
        $groupService = $this->getGroupService();
        $subscriptionService = $this->getSubscriptionService();
        
        $group    = $groupService->getGroupById($groupId);
        $request  = $this->getRequest();
        $user     = $this->getCurrentUser();

        if (!$group) {
            return $this->render('CobaseAppBundle:Group:notfound.html.twig',
                $this->mergeVariables()
            );
        }

        $subscriptionService->subscribe($group, $user);
        
        $this->get('session')->getFlashBag()->add('subscription.transaction', 'Your subscription to group "' . $group->getTitle() . '" has been added.');

        return $this->redirect($this->generateUrl('CobaseAppBundle_homepage',
            $this->mergeVariables()
        ));
    }

    /**
     * Unsubscribe a user from a group
     * 
     * @param $groupId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function unsubscribeAction($groupId)
    {
        $groupService = $this->getGroupService();
        $subscriptionService = $this->getSubscriptionService();

        $group    = $groupService->getGroupById($groupId);
        $request  = $this->getRequest();
        $user     = $this->getCurrentUser();

        if (!$group) {
            return $this->render('CobaseAppBundle:Group:notfound.html.twig',
                $this->mergeVariables()
            );
        }

        $subscriptionService->unsubscribe($group, $user);

        $this->get('session')->getFlashBag()->add('subscription.transaction', 'Your subscription to group "' . $group->getTitle() . '" has been removed.');

        return $this->redirect($this->generateUrl('CobaseAppBundle_homepage',
            $this->mergeVariables()
        ));
    }
    
    /**
     * Show all groups
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

        $groupService = $this->getGroupService();
        $groups = $groupService->getAllPublicGroups($limit, $orderBy, $order);

        return $this->render('CobaseAppBundle:Page:allGroups.html.twig',
            $this->mergeVariables(
                array(
                    'groups' => $groups
                )
            )
        );
    }

}