<?php
namespace Cobase\Component;

use Twig_Environment;

class EmailTemplate
{
    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @var string
     */
    private $template;

    /**
     * @param Twig_Environment  $twig
     * @param string            $template
     */
    public function __construct(Twig_Environment $twig, $template)
    {
        $this->twig     = $twig;
        $this->template = $template;
    }

    /**
     * @param array $data
     *
     * @return string
     */
    public function render(array $data)
    {
        return $this->twig->render($this->template, $data);
    }

}
