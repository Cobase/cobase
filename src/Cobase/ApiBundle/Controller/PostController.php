<?php

namespace Cobase\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostController extends FOSRestController
{
    /**
     * Collection get action
     * @var Request $request
     * @return array
     *
     * @Rest\View()
     */
    public function getPostsAction()
    {
        $data = $this->getDoctrine()->getRepository('CobaseAppBundle:Post')->findAll();
        $view = $this->view($data, 200)
            ->setTemplate("CobaseApiBundle:Post:getPosts.json.twig")
            ->setTemplateVar('posts')
        ;

        return $this->handleView($view);
    }

    public function getPostAction($id)
    {
        $post = $this->getDoctrine()->getRepository('CobaseAppBundle:Post')->findOneById($id);

        if (!$post instanceof Post) {
            throw new NotFoundHttpException('Post not found');
        }

        return array('post' => $post);
    }
}