<?php

namespace Cobase\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class GroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('isPublic', null,
                array(
                    'attr' => array('class' => 'checkbox-field'),
                    'label' => 'Public group',
                    'required' => false)
                )
            ->add('tags');
        ;
    }

    public function getName()
    {
        return 'cobase_appbundle_grouptype';
    }
}
