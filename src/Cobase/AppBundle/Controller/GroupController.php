<?php

namespace Cobase\AppBundle\Controller;

use Cobase\AppBundle\Controller\BaseController;
use Cobase\AppBundle\Entity\Group;
use Cobase\AppBundle\Form\GroupType;
use Cobase\AppBundle\Entity\Post;
use Cobase\AppBundle\Form\PostType;

use Eko\FeedBundle\Feed\Feed;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

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

        if ($this->getRequest()->getMethod() != 'POST') {
            return $this->redirect($this->generateUrl('CobaseAppBundle_group_new'));
        }

        if (!$this->processForm($form)) {
            return $this->render('CobaseAppBundle:Group:create.html.twig',
                $this->mergeVariables(
                    array(
                        'form'     => $form->createView(),
                    )
                )
            );
        }

        $groupUrl = $this->container->get('router')
            ->generate('CobaseAppBundle_group_view', array('groupId' => $group->getShortUrl()), true);

        // Create group
        $service->saveGroup($group, $user);

        // creating the ACL
        $aclProvider = $this->get('security.acl.provider');
        $objectIdentity = ObjectIdentity::fromDomainObject($group);
        $acl = $aclProvider->createAcl($objectIdentity);

        // retrieving the security identity of the currently logged-in user
        $securityIdentity = UserSecurityIdentity::fromAccount($user);

        // grant owner access
        $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
        $aclProvider->updateAcl($acl);

        $this->get('session')->getFlashBag()->add('group.message', 'Your new group "' . $group->getTitle() . '" has been created, thank you.');

        return $this->redirect($this->generateUrl('CobaseAppBundle_all_groups'));
    }

    /**
     * View Group
     *
     * @param $shortUrl
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction($groupId)
    {
        if ($redirect = $this->checkLoginRequirement('CobaseAppBundle_all_groups', false)) {
            return $redirect;
        }

        $post = new Post();

        $groupService = $this->getGroupService();
        $postService = $this->getPostService();
        $subscriptionService = $this->getSubscriptionService();

        $request    = $this->getRequest();
        $user       = $this->getCurrentUser();
        $form       = $this->createForm(new PostType(), $post);
        $group      = $groupService->getGroupById($groupId);
        $groups     = $groupService->getAllPublicGroups(null, 'b.title', 'ASC');
        $postsQuery  = $postService->getLatestPublicPostsForGroupQuery($group);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $postsQuery,
            $this->get('request')->query->get('page', 1) /*page number*/,
            $this->container->getParameter('posts_per_page') /*limit per page*/
        );

        $isSubscribed = false;
        if ($user) {
            $isSubscribed = $subscriptionService->hasUserSubscribedToGroup($group, $user);
        }

        if (!$group) {
            return $this->render('CobaseAppBundle:Group:notfound.html.twig',
                $this->mergeVariables()
            );
        }

        if ($request->getMethod() == 'POST') {
            if ($this->processForm($form)) {

                // Convert line breaks to BR tag
                $content = $post->getContent();
                $content = str_replace("\n", '<br/>', $content);

                $post->setContent($content);
                $post->setGroup($group);
                $post->setUser($user);

                $postService->savePost($post);

                // creating the ACL
                $aclProvider = $this->get('security.acl.provider');
                $objectIdentity = ObjectIdentity::fromDomainObject($post);
                $acl = $aclProvider->createAcl($objectIdentity);

                // retrieving the security identity of the currently logged-in user
                $securityIdentity = UserSecurityIdentity::fromAccount($user);

                // grant owner access
                $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
                $aclProvider->updateAcl($acl);

                #$this->sendMail("You have received new high five for an event",
                #    $group->getUser()->getEmail(),
                #    $this->renderView('PortalAppBundle:Page:newHighFiveEmail.txt.twig',
                #        array('event' => $event)
                #    ));

                $this->get('session')->getFlashBag()->add('group.message', 'Your post has been sent, thank you!');

                return $this->redirect($this->generateUrl('CobaseAppBundle_group_view',
               	    array(
                        'groupId' => $groupId,
                    )
                ));

            }
        }

        return $this->render('CobaseAppBundle:Group:view.html.twig',
            $this->mergeVariables(
                array(
                    'group'         => $group,
                    'groups'        => $groups,
                    'pagination'    => $pagination,
                    'form'          => $form->createView(),
                    'subscribed'    => $isSubscribed,
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

        if (!$group) {
            return $this->render('CobaseAppBundle:Group:notfound.html.twig',
                $this->mergeVariables()
            );
        }

        if ($group->getUser() !== $user && !$this->get('security.context')->isGranted('ROLE_ADMIN') ) {
            return $this->render('CobaseAppBundle:Group:noaccess.html.twig',
                $this->mergeVariables()
            );
        }

        $form = $this->createForm(new GroupType(), $group);

        if ($request->getMethod() == 'POST') {
            if ($this->processForm($form)) {
                $groupService->saveGroup($group, $user);

                $this->get('session')->getFlashBag()->add('group.message', 'Your changes to the group have been saved.');

                return $this->redirect($this->generateUrl('CobaseAppBundle_group_view',
                    array(
                        'groupId' => $groupId,
                    )
                ));
            }
        }

        return $this->render('CobaseAppBundle:Group:modify.html.twig',
            $this->mergeVariables(
                array(
                    'group'         => $group,
                    'form'          => $form->createView(),
                )
            )
        );
    }

    /**
     * @param $postId
     */
    public function deleteAction()
    {
        $groupService = $this->getGroupService();
        $subscriptionService = $this->getSubscriptionService();

        $user = $this->getCurrentUser();
        $groupId = $this->getRequest()->get('groupId');

        $url = $this->generateUrl(
            'CobaseAppBundle_all_groups'
        );

        $group = $groupService->getGroupById($groupId);

        $group->setDeleted(new \DateTime());
        $groupService->saveGroup($group);

        $subscriptions = $subscriptionService->getSubscriptionsForGroup($group);
        foreach ($subscriptions as $subscription) {
            $subscription->setDeleted(new \DateTime());
            $subscriptionService->updateSubscription($subscription);
        }

        $this->get('session')->getFlashBag()->add('group.message', 'Group has been successfully deleted.');

        return new Response(
            json_encode(
                array(
                    "success" => true,
                    "url"     => $url,
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
        if ($redirect = $this->checkLoginRequirement('CobaseAppBundle_all_groups', false)) {
            return $redirect;
        }

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

    public function feedAction($groupId)
    {
        $feed   = $this->get('eko_feed.feed.manager')->get('post');

        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            if ($this->container->getParameter('login_required')) {
                return new Response($feed->render('rss'));
            }
        }

        return $this->getFeedPosts($groupId);
    }

    /**
     * @param int $groupId
     * @return Response
     */
    protected function getFeedPosts($groupId)
    {
        $feed   = $this->get('eko_feed.feed.manager')->get('post');

        $group  = $this->getGroupService()->getGroupById($groupId);

        if ($group) {
            $posts = $this->getPostService()->getLatestPublicPostsForGroup($group, 50);
            $feed->addFromArray($posts);
        }

        return new Response($feed->render('rss'));
    }
}
