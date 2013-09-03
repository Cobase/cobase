<?php

namespace Cobase\AppBundle\Controller;

use Cobase\AppBundle\Controller\BaseController;

/**
 * User controller.
 */
class UserController extends BaseController
{
    /**
     * Show all users and their stats
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAllAction()
    {
        $service = $this->getUserService();
        $users = $service->getUsers();

        return $this->render('CobaseAppBundle:User:allUsers.html.twig',
            $this->mergeVariables(
                array(
                    'users' => $users,
                )
            )
        );
    }

    /**
     * View user's page, groups and posts created
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction($username)
    {
        $userService = $this->getUserService();

        $user = $userService->getUserByUsername($username);

        if (!$user) {
            return $this->render('CobaseAppBundle:User:notfound.html.twig',
                $this->mergeVariables()
            );
        }

        $userGroups = $userService->findAllGroupsByUser($user);
        $userPosts = $userService->findAllPostsByUser($user);

        $groups = $userService->filterUnlistedGroups($userGroups, $this->getCurrentUser());
        $posts = $userService->filterUnlistedGroupPosts($userPosts, $this->getCurrentUser());

        return $this->render('CobaseAppBundle:User:view.html.twig',
            $this->mergeVariables(
                array(
                    'user'       => $user,
                    'userGroups' => $groups,
                    'userPosts'  => $posts,
                )
            )
        );
    }

}