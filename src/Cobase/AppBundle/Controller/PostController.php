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

        return $this->render('CobaseAppBundle:Page:allPosts.html.twig',
            $this->mergeVariables(
                array(
                    'highfives' => $posts,
                )
            )
        );
    }

}