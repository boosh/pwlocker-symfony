<?php

namespace TenK\PwLockerBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TenK\PwLockerBundle\Entity\Password;
use TenK\PwLockerBundle\Form\PasswordType;

class DefaultController extends Controller
{
    
    public function indexAction($name)
    {
        return $this->render('TenKPwLockerBundle:Default:index.html.twig', array('name' => $name));
    }
    
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
        
        $passwords = $this->getDoctrine()
            ->getRepository('TenKPwLockerBundle:Password')
            ->findAll();
        
        return $this->render('TenKPwLockerBundle:Default:password_list.html.twig', 
            array('passwords' => $passwords,
                'form' => $form->createView()));
    }
    
    public function createPasswordAction()
    {
        $password = new Password();
        $password->setTitle("Test pw");
        $password->setUsername("myuser");
        $password->setPassword("testpassword");
        
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($password);
        $em->flush();
        
        return new Response('Created password id '.$password->getId());
    }
    
    public function showPasswordAction($id)
    {
        $password = $this->getDoctrine()
            ->getRepository('TenKPwLockerBundle:Password')
            ->findOneById($id);
        
        if (!$password) {
            throw $this->createNotFoundException("Can't find that password'");
        }
        
        return new Response("Password has title " . $password->getTitle());
    }
}
