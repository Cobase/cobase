<?php

namespace Cobase\AppBundle\Controller;

use Cobase\AppBundle\Controller\BaseController;
use Cobase\AppBundle\Entity\Group;
use Cobase\AppBundle\Form\GroupType;
use Cobase\AppBundle\Entity\Post;
use Cobase\AppBundle\Form\PostType;

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

        return $this->render('CobaseAppBundle:Group:create.html.twig', array(
            'form'     => $form->createView(),
            'gravatar' => $gravatarGiven,
            'latestGroups' => $latestGroups,
        ));
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
            return $this->render('CobaseAppBundle:Group:create.html.twig', array(
                'form' => $form->createView(),
                'latestGroups' => $latestGroups,
            ));
        }

        $groupUrl = $this->container->get('router')
            ->generate('CobaseAppBundle_group_view', array('groupId' => $group->getShortUrl()), true);

        $service->saveGroup($group, $user);
  
        if ($group->getIsPublic()) {
            return $this->redirect($this->generateUrl('CobaseAppBundle_homepage'));
        } else {
            return $this->render('CobaseAppBundle:Group:unlisted-info.html.twig', array(
                'group'        => $group,
                'groupUrl'     => $groupUrl,
                'latestGroups' => $latestGroups,
            ));
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

        $request  = $this->getRequest();
        $form     = $this->createForm(new PostType(), $post);
        $user     = $this->getCurrentUser();
        $group    = $groupService->getGroupById($groupId);

        if (!$group) {
            return $this->render('CobaseAppBundle:Group:notfound.html.twig', array());
        }

        $latestGroups = $groupService->getLatestPublicGroups($this->container->getParameter('cobase_app.comments.max_latest_groups'));

        $allowModify   = false;

        if ($user) {
            if ($group->getUser() === $user) {
                $allowModify = true;
            
            }
        }
        
        if ($request->getMethod() == 'POST') {
            if ($this->processForm($form)) {
                $postService->savePost($post, $group, $user);

                $emailTitle = "Someone posted to Cobase group '" . $group->getTitle() . "'";

                $this->sendMail($emailTitle,
                                $group->getUser()->getEmail(),
                                $this->renderView('CobaseAppBundle:Page:newPostEmail.txt.twig',
                                    array('group' => $group)
                                ));

                $this->get('session')->getFlashBag()->add('post.saved', 'New post has been saved, thank you!');

                return $this->forward('CobaseAppBundle:Group:view', array(
                    'groupId' => $groupId,
                ));
            }
        }

        return $this->render('CobaseAppBundle:Group:view.html.twig', array(
            'group'         => $group,
            'latestGroups'  => $latestGroups,
            'form'          => $form->createView(),
            'allowModify'   => $allowModify,
        ));
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
            return $this->render('CobaseAppBundle:Group:notfound.html.twig', array('latestGroups' => $latestGroups));
        }

        if ($group->getUser() !== $user) {
            return $this->render('CobaseAppBundle:Group:noaccess.html.twig', array('latestGroups' => $latestGroups));
        }

        $form = $this->createForm(new GroupType(), $group);

        if ($request->getMethod() == 'POST') {
            if ($this->processForm($form)) {
                $groupService->saveGroup($group, $user);

                $this->get('session')->getFlashBag()->add('group.saved', 'Your changes have been saved!');

                return $this->redirect($this->generateUrl('CobaseAppBundle_group_view', array(
                    'groupId' => $groupId,
                )));
            }
        }

        return $this->render('CobaseAppBundle:Group:modify.html.twig', array(
            'group'         => $group,
            'latestGroups'  => $latestGroups,
            'form'          => $form->createView(),
        ));
    }

    /**
     * Search for Groups
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchAction()
    {
        $searchWord = $this->getRequest()->get('searchWord');
        $groupService = $this->getGroupService();
        $groups       = $groupService->findAllBySearchWord($searchWord);

        return $this->render('CobaseAppBundle:Group:searchResults.html.twig', array(
            'groups'    => $groups,
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

        return $this->render('CobaseAppBundle:Page:allGroups.html.twig', array(
            'groups'    => $groups
        ));
    }

}