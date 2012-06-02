<?php

namespace TenK\PwLockerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PasswordType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('username')
            ->add('password')
            ->add('url', null, array('label' => 'Site URL'))
            ->add('notes')
        ;
    }
    
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'TenK\PwLockerBundle\Entity\Password',
            // remove csrf protection for now
            'csrf_protection' => false,
        );
    }

    public function getName()
    {
        // set the name so it matches how Django generates forms. That way
        // we don't have to change our Backbone.js code.
        return 'id';
    }
}
