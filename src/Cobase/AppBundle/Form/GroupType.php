<?php

namespace Cobase\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class GroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null,
                array(
                    'attr' => array('style' => 'width:90%'),
                    'required' => true
                )
            )
            ->add('description', null,
                array(
                    'attr' => array('style' => 'width:90%'),
                    'required' => true
                )
            )
            ->add('isPublic', null,
                array(
                    'attr' => array('class' => 'checkbox-field'),
                    'label' => 'Public group',
                    'required' => false
                )
            )
            ->add('tags', null,
                array(
                    'attr' => array('style' => 'width:50%'),
                    'required' => false
                )
            );
        ;
    }

    public function getName()
    {
        return 'cobase_appbundle_grouptype';
    }
}
