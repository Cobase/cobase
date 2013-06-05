<?php
namespace Cobase\DemoBundle\Service;

use Cobase\DemoBundle\Form\Model\CatModel;
use Cobase\DemoBundle\Form\Type\CatType;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;

class CatService
{
    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @param FormFactory $formFactory
     */
    public function __construct(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @param CatModel $catModel
     * @return Form
     */
    public function getCatForm(CatModel $catModel)
    {
        return $this->formFactory->create(
            new CatType(get_class($catModel)), $catModel
        );
    }

    /**
     * @param Form $form
     *
     * @return string
     */
    public function patCatByForm(Form $form)
    {
        $catModel = $form->getData();

        return str_repeat($catModel->getName() . " ", $catModel->getAge() );
    }
}