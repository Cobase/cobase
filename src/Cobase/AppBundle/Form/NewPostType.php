<?php

namespace Cobase\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class NewPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content', 'textarea',
            array(
                'attr' => array('class' => 'new-post'),
                'label' => 'Share an Update:',
                'required' => true)
            )
            ->add('group', 'entity',
                array(
                    'class' => 'CobaseAppBundle:Group',
                    'property' => 'title',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('g')
                            ->orderBy('g.title', 'ASC');
                        },
                    'required' => true,
                    'label' => 'Choose a group:'
                )
            );
    }

    public function getName()
    {
        return 'cobase_appbundle_newposttype';
    }
}

