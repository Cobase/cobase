<?php

namespace Cobase\AppBundle\Service;

use FOS\CommentBundle\Entity\ThreadManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class CommentService
{
    /**
     * @var ThreadManager
     */
    private $commentThreadManager;

    /**
     * @var Router
     */
    private $router;

    /**
     * @param ThreadManager $commentThreadManager
     * @param Router        $router
     */
    public function __construct(
        ThreadManager $commentThreadManager,
        Router $router
    ) {
        $this->commentThreadManager = $commentThreadManager;
        $this->router = $router;
    }

    /**
     * Initialize comment threads for each post, create new thread if none exists.
     *
     * @param array $posts
     */
    public function initializeCommentThreads(array $posts)
    {
        foreach ($posts as $post) {
            $thread = $this->commentThreadManager->findThreadById($post->getId());

            if (!$thread) {
                $thread = $this->commentThreadManager->createThread($post->getId());

                $postUrl = $this->router
                    ->generate('CobaseAppBundle_post_view', array(
                        'postId'  => $post->getId(),
                        'groupId' => $post->getGroup()->getShortUrl()
                    ), true);

                $permalink = urldecode($postUrl);
                $thread->setPermalink($permalink);

                $this->commentThreadManager->saveThread($thread);
            }

            $post->setCommentThread($thread);
        }
    }
}
