<?php

namespace Cobase\AppBundle\Controller;

use Cobase\AppBundle\Controller\BaseController;
use Cobase\AppBundle\Entity\QuickPost;
use Cobase\AppBundle\Form\QuickPostType;

/**
 * Post controller.
 */
class PostController extends BaseController
{
    /**
     * Show all Posts
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAllAction($orderType = 'asc')
    {
        $order = 'asc';
        if ($orderType == 'desc') {
            $order = 'desc';
        }

        $limit = null;

        $postService = $this->getPostService();
        $posts = $postService->getAllPostsforPublicGroups($limit, $orderType);

        return $this->render('CobaseAppBundle:Page:allPosts.html.twig', array(
            'highfives' => $posts,
        ));
    }

    /**
     * Show all Quick Posts for current user
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAllQuickPostsAction($orderType = 'asc')
    {
        $postService = $this->getPostService();
        $groupService    = $this->getGroupService();
        $latestGroups    = $groupService->getLatestPublicGroups($this->container->getParameter('cobase_app.comments.max_latest_groups'));

        $user = $this->getCurrentUser();
        $limit = null;

        if (!$user) {
            return $this->render('CobaseAppBundle:Group:noaccess.html.twig', array('latestGroups' => $latestGroups));
        }

        $order = 'asc';
        if ($orderType == 'desc') {
            $order = 'desc';
        }

        $quickPosts = $postService->getAllQuickPostsForUser($user, $limit, $order);

        return $this->render('CobaseAppBundle:Page:allQuickPosts.html.twig', array(
            'quickPosts' => $quickPosts,
            'latestGroups'   => $latestGroups,
        ));
    }

    /**
     * Send a quick high five to a specific user
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function quickAction($username)
    {
        $currentUser     = $this->getCurrentUser();
        $groupService    = $this->getGroupService();
        $highfiveService = $this->getPostService();

        $latestGroups    = $groupService->getLatestPublicGroups($this->container->getParameter('cobase_app.comments.max_latest_groups'));

        $enableCaptcha = true;
        if ($currentUser) {
            $enableCaptcha = false;
        } else {
            if (!$this->container->getParameter('cobase_app.enable_recaptha')) {
                $enableCaptcha = false;
            }
        }

        $quickPost   = new QuickPost($enableCaptcha);
        $request         = $this->getRequest();
        $form            = $this->createForm(new QuickPostType(), $quickPost);

        if ($enableCaptcha === false) {
            $form->remove('recaptcha');
            $form->remove('author');
        }

        $userManager     = $this->container->get('fos_user.user_manager');
        $user            = $userManager->findUserByUsername($username);

        if (!$user) {
            return $this->render('CobaseAppBundle:Group:userNotFound.html.twig', array());
        }

        $showForm = true;

        if ($request->getMethod() == 'POST') {
            if ($this->processForm($form)) {
                $quickPost->setUser($user);

                if ($currentUser) {
                    $quickPost->setAuthor($currentUser->getName());
                }

                $highfiveService->saveQuickPost($quickPost);

                $this->sendMail("You have received new quick high five",
                    $user->getEmail(),
                    $this->renderView('CobaseAppBundle:Page:newQuickPostEmail.txt.twig',
                        array(
                            'to'   => $user,
                            'from' => $quickPost->getAuthor(),
                        )
                    ));

                $this->get('session')->getFlashBag()->add('highfive.saved', 'Your quick high hive has been sent, thank you!');
                $showForm = false;
            }
        }

        return $this->render('CobaseAppBundle:Post:quick.html.twig', array(
            'form' => $form->createView(),
            'user' => $user,
            'latestGroups' => $latestGroups,
            'showForm' => $showForm,
        ));
    }

}