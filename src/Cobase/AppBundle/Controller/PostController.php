<?php

namespace Cobase\AppBundle\Controller;

use Cobase\AppBundle\Controller\BaseController;
use Cobase\AppBundle\Entity\Post;
use Cobase\AppBundle\Form\PostType;

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

    /**
     * Modify Post
     *
     * @param $postId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function modifyAction($postId)
    {
        $postService = $this->getPostService();

        $post    = $postService->getPostById($postId);
        $request = $this->getRequest();
        $user    = $this->getCurrentUser();

        if (!$post) {
            return $this->render('CobaseAppBundle:Post:notfound.html.twig',
                $this->mergeVariables(
                    array()
                )
            );
        }

        if ($post->getUser() !== $user) {
            return $this->render('CobaseAppBundle:Post:noaccess.html.twig',
                $this->mergeVariables(
                    array()
                )
            );
        }

        // If not updating, convert BR tags to line breaks
        if ($request->getMethod() !== 'POST') {
            $post->setContent(preg_replace('/\<br\/\>/', "\n", $post->getContent()));
        }
        
        $form = $this->createForm(new PostType(), $post);

        if ($request->getMethod() == 'POST') {
            if ($this->processForm($form)) {
                
                // Convert line breaks to BR tag
                $content = $post->getContent();
                $content = str_replace("\n", '<br/>', $content);
                $post->setContent($content);

                // Save the posti modifications
                $postService->savePost($post);

                $this->get('session')->getFlashBag()->add('post.message', 'Your changes to the post have been saved.');

                return $this->redirect($this->generateUrl('CobaseAppBundle_group_view',
                    array(
                        'groupId' => $post->getGroup()->getShortUrl(),
                    )
                ));
            }
        }
 
        return $this->render('CobaseAppBundle:Post:modify.html.twig',
            $this->mergeVariables(
                array(
                    'post'          => $post,
                    'form'          => $form->createView(),
                )
            )
        );
    }

}