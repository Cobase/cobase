<?php
namespace Cobase\AppBundle\Twig\Extensions;

use Cobase\AppBundle\Entity\Post;

use Cobase\AppBundle\Service\PostService;
use Cobase\AppBundle\Service\UserService;
use Twig_Extension;
use Twig_Environment;

class Like extends Twig_Extension
{
    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var PostService
     */
    private $postService;

    /**
     * @param Twig_Environment  $twig
     * @param UserService       $userService
     * @param PostService       $postService
     */
    public function __construct(Twig_Environment $twig, UserService $userService, PostService $postService)
    {
        $this->twig         = $twig;
        $this->userService  = $userService;
        $this->postService  = $postService;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'cobase_likes' => new \Twig_Function_Method(
                $this, 'likes', array('is_safe' => array('html') )
            ),
        );
    }

    /**
     * @param Post $post
     *
     * @return string
     */
    public function likes(Post $post)
    {
        return $this->twig->render('CobaseAppBundle:Twig:likes.html.twig', array(
            'user'      => $this->userService->getCurrentUser(),
            'post'      => $post,
            'likeCount' => $this->postService->getLikeCount($post),
        ) );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Cobase_app_like';
    }
}
