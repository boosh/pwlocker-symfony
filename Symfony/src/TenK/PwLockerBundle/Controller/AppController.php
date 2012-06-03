<?php

namespace TenK\PwLockerBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TenK\PwLockerBundle\Entity\Password;
use TenK\PwLockerBundle\Form\PasswordType;

class AppController extends Controller
{
    /**
     * Displays the main app page with a form for adding new passwords
     */
    public function passwordListAction(Request $request)
    {
        return $this->render('TenKPwLockerBundle:Default:password_list.html.twig', 
            array('form' => $this->createForm(new PasswordType(), new Password())
                    ->createView()
            )
        );
    }
}
