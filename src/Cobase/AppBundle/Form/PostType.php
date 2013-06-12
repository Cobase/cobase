<?php

namespace Cobase\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content', 'textarea',
            array(
                'attr' => array('class' => 'new-post'),
                'label' => 'Share an Update:',
                'required' => true));
    }

    public function getName()
    {
        return 'cobase_appbundle_posttype';
    }
}

