<?php
namespace Cobase\DemoBundle\Controller;

use \Closure;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;

class BaseController extends Controller
{
    /**
     * @param Form $form
     * @return bool
     */
    public function mediateForm(Form $form)
    {
        $form->bind($this->getRequest() );

        return $form->isValid();
    }
}
