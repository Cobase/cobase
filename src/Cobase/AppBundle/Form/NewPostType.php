<?php

namespace Cobase\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class NewPostType extends AbstractType
{
    private $subscriptions;

    public function __construct($subscriptions)
    {
        $this->subscriptions = $subscriptions;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($this->subscriptions as $subscription) {
            $groupIds[] = $subscription->getGroup()->getId();
        }
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
                    'query_builder' => function(EntityRepository $er) use ($groupIds) {
                        return $er->createQueryBuilder('g')
                            ->where('g.id IN (:groupIds)')
                            ->setParameter('groupIds', $groupIds);
                        }
                )
            );
    }

    public function getName()
    {
        return 'cobase_appbundle_newposttype';
    }
}

