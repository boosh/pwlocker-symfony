<?php

namespace TenK\PwLockerBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

use TenK\PwLockerBundle\Entity\Password;
use TenK\PwLockerBundle\Form\PasswordType;
use TenK\PwLockerBundle\ApiResource\PasswordResource;

class ApiController extends Controller
{
    /**
     * Create a new Password
     */
    public function createPasswordAction(Request $request)
    {
        $password = new Password();
        
        $response = $this->processForm($password, $request);
        
        if ($response === false)
        {
            // need to set the right status code
            $response = new Response(json_encode(array(
                'error' => 'Unable to update password')
            ), 500);
        }
        
        return $response;
    }

    /**
     * Handle submitted Password data
     */
    protected function processForm($password, $request)
    {
        $form = $this->createForm(new PasswordType(), $password);
        $form->bindRequest($request);
        
        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($password);
            $em->flush();
            
            return new Response(json_encode($this->passwordsToArray($password)));
        }
        
        // should throw an exception here that can be caught to show the
        // form was invalid.
        return false;
    }

    /**
     * Update a Password
     */
    public function updatePasswordAction(Request $request, $id)
    {
        $password = $this->getDoctrine()
            ->getRepository('TenKPwLockerBundle:Password')
            ->findOneById($id);
        
        if (!$password) 
        {
            throw $this->createNotFoundException("404 NOT FOUND");
        }
        
        $response = $this->processForm($password, $request);
        
        if ($response === false)
        {
            // need to set the right status code
            $response = new Response(json_encode(array(
                'error' => 'Unable to update password')
            ), 500);
        }
        
        return $response;
    }

    /**
     * Get a single Password
     */
    public function getPasswordAction($id)
    {
        $password = $this->getDoctrine()
            ->getRepository('TenKPwLockerBundle:Password')
            ->findOneById($id);
        
        if (!$password) 
        {
            throw $this->createNotFoundException("404 NOT FOUND");
        }
        
        return new Response(json_encode($this->passwordsToArray(array($password))));
    }

    /**
     * Returns a list of Passwords
     */
    public function listPasswordsAction(Request $request)
    {
        $passwords = $this->getDoctrine()
            ->getRepository('TenKPwLockerBundle:Password')
            ->findAll();
        
        return new Response(json_encode($this->passwordsToArray($passwords)));
    }
    
    /**
     * Convert a Password to an array. Converts each Password to a
     * PasswordResource first so we can call additional methods which are
     * only relevant to the API.
     */
    protected function passwordsToArray($passwords)
    {
        $responseArray = array();
        
        foreach ($passwords as $password)
        {
            $password = new PasswordResource($password, $this->get('router'));
            $responseArray[] = array(
                'id' => $password->getId(),
                'username' => $password->getUsername(),
                'password' => $password->getPassword(),
                'title' => $password->getTitle(),
                'url' => $password->getUrl(),
                'notes' => $password->getNotes(),
                'resource_url' => $password->getResourceUrl(),
                'is_owner' => $password->getIsOwner(),
                'shares' => array()
            );
        }

        return $responseArray;
    }
}
