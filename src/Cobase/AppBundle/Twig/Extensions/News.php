<?php
namespace Cobase\AppBundle\Twig\Extensions;

use Cobase\AppBundle\Entity\Post;

use Twig_Extension;
use Twig_Environment;

class News extends Twig_Extension
{
    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @param Twig_Environment  $twig
     * @param UserService       $userService
     * @param PostService       $postService
     */
    public function __construct(Twig_Environment $twig)
    {
        $this->twig         = $twig;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'cobase_news' => new \Twig_Function_Method(
                $this, 'news', array('is_safe' => array('html') )
            ),
        );
    }

    /**
     * @return string
     */
    public function news()
    {
        $content = null;
        $displayNews = false;

        $file = __DIR__ . '/../../../../../app/config/news.txt';

        if (file_exists($file)) {
            $content = trim(file_get_contents($file));
            $displayNews = true;
        }

        return $this->twig->render('CobaseAppBundle:Twig:news.html.twig', array(
            'displayNews' => $displayNews,
            'newsContent' => $content,
        ) );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Cobase_app_news';
    }
}
