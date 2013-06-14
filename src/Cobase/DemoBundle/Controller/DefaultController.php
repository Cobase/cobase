<?php
namespace Cobase\DemoBundle\Controller;

use Cobase\DemoBundle\Form\Model\CatModel;

class DefaultController extends BaseController
{
    public function indexAction()
    {
        $service    = $this->getCatService();
        $form       = $service->getCatForm(new CatModel());

        return $this->render('CobaseDemoBundle:Default:index.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function createAction()
    {
        $service    = $this->getCatService();
        $form       = $service->getCatForm(new CatModel() );

        if (!$this->processForm($form)) {
            return $this->render('CobaseDemoBundle:Default:index.html.twig', array(
                           'form' => $form->createView(),
            ));
        }

        $pats = $service->patCatByForm($form);
        $this->get('session')->setFlash('pats', $pats);

        return $this->redirect($this->generateUrl('CobaseDemoBundle_demo_meoow'));
    }

    public function meoowAction()
    {
        return $this->render('CobaseDemoBundle:Default:meoow.html.twig', array(
            'message' => $this->get('session')->getFlash("pats"),
        ));
    }

    /**
     * @return \Cobase\DemoBundle\Service\CatService
     */
    protected function getCatService()
    {
        return $this->get('portal_demo.service.cat');
    }
}
