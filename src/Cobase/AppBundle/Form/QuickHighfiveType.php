<?php

namespace Cobase\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class QuickHighfiveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('author', null,
            array('label'     => 'Your name',
                  'required' => false)
        );
        $builder->add('comment');
        $builder->add('recaptcha', 'ewz_recaptcha');
    }

    public function getName()
    {
        return 'cobase_appbundle_quickhighfivetype';
    }

}

