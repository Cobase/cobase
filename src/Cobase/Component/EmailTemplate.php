<?php
namespace Cobase\Component;

use Symfony\Component\Translation\Translator;

use Twig_Environment;

class EmailTemplate
{
    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var string
     */
    private $template;

    /**
     * @var string
     */
    private $subject;

    /**
     * @param Twig_Environment      $twig
     * @param Translator            $translator
     * @param string                $template
     * @param string                $subject translated string or translation key
     */
    public function __construct(Twig_Environment $twig, Translator $translator, $template, $subject)
    {
        $this->twig         = $twig;
        $this->translator   = $translator;
        $this->template     = $template;
        $this->subject      = $subject;
    }

    /**
     * @param $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->translator->trans($this->subject);
    }

    /**
     * @param array $data
     *
     * @return string
     */
    public function renderPlainText(array $data)
    {
        return $this->twig->render($this->template, $data);
    }
}
