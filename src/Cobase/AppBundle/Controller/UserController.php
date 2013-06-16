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

}