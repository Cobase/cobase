<?php

namespace Cobase\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('externalUrl', null,
                array('label' => 'External URL to this event',
                      'required' => false)
                )

            ->add('isPublic', null,
                array(
                    'attr' => array('class' => 'checkbox-field'),
                    'label' => 'Anyone can see this event',
                    'required' => false)
                )
            ->add('tags');
        ;
    }

    public function getName()
    {
        return 'cobase_appbundle_eventtype';
    }
}
