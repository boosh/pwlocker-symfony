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
            ->add('id')
            ->add('resource_url')
            ->add('is_owner')
            ->add('shares')
            ->add('maskedPassword')
        ;
    }
}
