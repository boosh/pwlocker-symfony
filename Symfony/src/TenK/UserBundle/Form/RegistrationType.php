<?php

namespace TenK\UserBundle\Form;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationType extends BaseType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('firstName', null, array('label' => 'First name'));
        $builder->add('lastName');
        parent::buildForm($builder, $options);
    }

    public function getName()
    {
        return 'tenk_user_registration';
    }
}

