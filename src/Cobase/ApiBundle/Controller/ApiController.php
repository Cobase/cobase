<?php

namespace Cobase\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ApiController extends Controller
{
    public function indexAction()
    {
        return $this->render('CobaseApiBundle:Api:index.html.twig', array());
    }
}
