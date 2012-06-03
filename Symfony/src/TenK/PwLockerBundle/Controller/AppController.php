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
     * Displays a list of passwords
     */
    public function passwordListAction(Request $request)
    {
        $password = new Password();
        
        $form = $this->createForm(new PasswordType(), $password);

        if ($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);
            
            if ($form->isValid())
            {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($password);
                $em->flush();
                return $this->redirect($this->generateUrl('TenKPwLockerBundle_password_list'));
            }
        }
        
        return $this->render('TenKPwLockerBundle:Default:password_list.html.twig', 
            array('form' => $form->createView()));
    }
}
