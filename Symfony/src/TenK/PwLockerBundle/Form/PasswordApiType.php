<?php

namespace TenK\PwLockerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

use TenK\PwLockerBundle\Form\PasswordType;

class PasswordApiType extends PasswordType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            // ->add('id', 'text', array('property_path' => false))
            ->add('resource_url', 'text', array('property_path' => false))
            ->add('is_owner', 'text', array('property_path' => false))
            ->add('shares', 'choice', array('property_path' => false))
            ->add('maskedPassword', 'text', array('property_path' => false))
        ;
    }
}
