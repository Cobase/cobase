<?php
namespace Cobase\AppBundle\Twig\Extensions;

use Cobase\AppBundle\Entity\Post;

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
     * @param Twig_Environment $twig
     * @param UserService $userService
     */
    public function __construct(Twig_Environment $twig, UserService $userService)
    {
        $this->twig         = $twig;
        $this->userService  = $userService;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'cobase_like' => new \Twig_Function_Method(
                $this, 'like', array('is_safe' => array('html') )
            ),
        );
    }

    /**
     * @param Post $post
     *
     * @return string
     */
    public function like(Post $post)
    {
        return $this->twig->render('CobaseAppBundle:Twig:likes.html.twig', array(
            'user' => $this->userService->getCurrentUser(),
            'post' => $post,
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
