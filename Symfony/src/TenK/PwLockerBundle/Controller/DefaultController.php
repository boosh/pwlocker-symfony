<?php

namespace TenK\PwLockerBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TenK\PwLockerBundle\Entity\Password;
use TenK\PwLockerBundle\Form\PasswordType;

class DefaultController extends Controller
{
    /**
     * Displays the home page
     */
    public function indexAction()
    {
        return $this->render('TenKPwLockerBundle:Default:index.html.twig');
    }
}
