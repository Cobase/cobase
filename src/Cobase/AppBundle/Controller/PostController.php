<?php

namespace Cobase\AppBundle\Controller;

use Cobase\AppBundle\Controller\BaseController;
use Cobase\AppBundle\Entity\Post;
use Cobase\AppBundle\Form\PostType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

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
                $this->mergeVariables()
            );
        }

        if ($post->getUser() !== $user) {
            return $this->render('CobaseAppBundle:Post:noaccess.html.twig',
                $this->mergeVariables()
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

    /**
     * @param $postId
     */
    public function moveAction()
    {
        $postService = $this->getPostService();
        $groupService = $this->getGroupService();

        $groupId = $this->getRequest()->get('groupId');
        $postId = $this->getRequest()->get('postId');

        $url = $this->generateUrl(
            'CobaseAppBundle_group_view',
            array(
                'groupId' => $groupId,
            ),
            true
        );
        
        $group = $groupService->getGroupById($groupId);
        $post = $postService->getPostById($postId);
        
        $post->setGroup($group);
        $postService->savePost($post);

        $this->get('session')->getFlashBag()->add('post.message', 'Post has been successfully moved.');

        return new Response(
            json_encode(
                array(
                    "success" => true,
                    "url"     => $url,
                )
            )
        );
    }

    public function likePostAction($postId)
    {
        $post = $this->getPostService()->getPostById($postId);
        $user = $this->getCurrentUser();

        if ($user->likesPost($post)) {
            return $this->createJsonFailureResponse(array(
                'message' => 'You already like this post',
            ));
        }

        $this->getPostService()->likePost($post, $user);

        return $this->getJsonResponse(array('success' => true, 'message' => 'You now like this post'));
    }

    public function unlikePostAction($postId)
    {
        $post = $this->getPostService()->getPostById($postId);
        $user = $this->getCurrentUser();

        if (!$user->likesPost($post)) {
            return $this->createJsonFailureResponse(array(
                'message' => "You didn't like this post previously",
            ));
        }

        $this->getPostService()->unlikePost($post, $user);

        return $this->getJsonResponse(array('success' => true, 'message' => "You don't like this post anymore"));
    }

    public function getLikesAction($postId)
    {
        $post = $this->getPostService()->getPostById($postId);

        if (!$post) {
            throw new ResourceNotFoundException('No such post found.');
        }

        $likes = $this->getPostService()->getLikes($post);

        $likesArr = array();

        foreach ($likes as $like) {
            $likesArr[] = array(
                'userId'    => $like->getUser()->getId(),
                'username'  => $like->getUser()->getUsername(),
            );
        }

        return $this->getJsonResponse(array('success' => true, 'likes' => $likesArr));
    }
}