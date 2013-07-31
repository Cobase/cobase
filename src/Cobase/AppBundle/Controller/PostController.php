<?php

namespace Cobase\AppBundle\Controller;

use Cobase\AppBundle\Controller\BaseController;
use Cobase\AppBundle\Entity\Post;
use Cobase\AppBundle\Form\PostType;
use Cobase\AppBundle\Form\NewPostType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Post controller.
 */
class PostController extends BaseController
{
    public function viewAction($postId)
    {
        $post = $this->getPostService()->getPostById($postId);

        if (!$post) {
            return $this->render('CobaseAppBundle:Post:notfound.html.twig',
                $this->mergeVariables()
            );
        }

        return $this->render('CobaseAppBundle:Post:view.html.twig',
            $this->mergeVariables(
                array(
                    'post' => $post,
                )
            )
        );
    }

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

        if ($post->getUser() !== $user && !$this->get('security.context')->isGranted('ROLE_ADMIN') ) {
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

    /**
     * @param $postId
     */
    public function deleteAction()
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

        $post->setDeleted(new \DateTime());
        $postService->savePost($post);

        $this->get('session')->getFlashBag()->add('post.message', 'Post has been successfully deleted.');

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
     * To create a new post from the bookmarklet
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction()
    {
        $postService = $this->getPostService();
        $post = new Post;

        /**
         * if there is an url variable in the request someone is using the bookmarklet
         */
        $request = $this->getRequest();
        if($request->query->has('url')) {
            $url = $request->query->get('url');

            $content = false;
            if($request->query->has('content')) {
                $content = $request->query->get('content');
            }

            $metadata = $postService->fetchMetadataFromUrl($url);

            $post->setBookmarkletContent($content, $metadata, $url);
        }

        $form = $this->createForm(new NewPostType(), $post);

        $postService = $this->getPostService();

        if ($request->getMethod() == 'POST') {
            if ($this->processForm($form)) {

                // Convert line breaks to BR tag
                $content = $post->getContent();
                $content = str_replace("\n", '<br/>', $content);
                $post->setContent($content);

                // Save the posti modifications
                $postService->savePost($post);

                $this->get('session')->getFlashBag()->add('post.message', 'Your post has been saved.');

                return $this->redirect($this->generateUrl('CobaseAppBundle_group_view',
                    array(
                        'groupId' => $post->getGroup()->getShortUrl(),
                        'closeFancyBox' => true,
                    )
                ));
            }
        }

        return $this->render('CobaseAppBundle:Post:new.html.twig',
            $this->mergeVariables(
                array(
                    'post'          => $post,
                    'form'          => $form->createView(),
                )
            )
        );
    }

    /**
     * To execute the javascript code of the bookmarklet
     */
    public function bookmarkletAction()
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/javascript');
        $response->setContent($this->renderView('CobaseAppBundle:Post:bookmarklet.js.twig'));
        return $response;
    }

    public function likePostAction($postId)
    {
        $post = $this->getPostService()->getPostById($postId);
        $user = $this->getCurrentUser();

        if (!$user) {
            return $this->createJsonFailureResponse(array(
                'message' => "You need to be logged in to do this action.",
            ));
        }

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

        if (!$user) {
            return $this->createJsonFailureResponse(array(
                'message' => "You need to be logged in to do this action.",
            ));
        }

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
                'username'  => $like->getUser()->getUsernameCanonical(),
                'name'  => $like->getUser()->getName(),
            );
        }

        return $this->getJsonResponse(array('success' => true, 'likes' => $likesArr));
    }

    /**
     * Generate the article feed
     *
     * @return Response XML Feed
     */
    public function feedAction()
    {
        $feed = $this->get('eko_feed.feed.manager')->get('post');

        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            if ($this->container->getParameter('login_required')) {
                return new Response($feed->render('rss'));
            }
        }

        $posts = $this->getPostService()->getLatestPostsForPublicGroups(50);

        $feed = $this->get('eko_feed.feed.manager')->get('post');
        $feed->addFromArray($posts);

        return new Response($feed->render('rss'));
    }
}