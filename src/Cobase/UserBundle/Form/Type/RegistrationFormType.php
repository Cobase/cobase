<?php

/*
 * This file overrides that of the FOSUserBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cobase\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    /**
     * @var
     */
    protected $translator;

    /**
     * @param string $userClass
     * @param $translator
     */
    public function __construct($userClass, $translator) {
        parent::__construct($userClass);
        $this->translator = $translator;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', null, array('label' => $this->translator->trans('form.realname')));
        $builder->add('email', null, array('label' => $this->translator->trans('form.email')));
        parent::buildForm($builder, $options);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'portal_user_registration';
    }
}