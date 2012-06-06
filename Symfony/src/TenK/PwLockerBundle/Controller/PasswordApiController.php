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
use TenK\PwLockerBundle\Form\PasswordApiType;
use TenK\PwLockerBundle\ApiResource\PasswordResource;

class PasswordApiController extends Controller
{
    /**
     * Returns the currently authenticated user.
     */
    protected function getUser()
    {
        return $this->get('security.context')->getToken()->getUser();
    }
    
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
        $form = $this->createForm(new PasswordApiType(), $password);
        
        // get the content - this allows us to pull in data submitted using
        // PUT as well as POST
        $dataStr = $request->getContent();
        
        // decode the json to an associative array
        $data = json_decode($dataStr, true);
        
        $data['shares'] = '';
        $form->bind($data);
        
        if ($form->isValid())
        {
            if (!$password->getCreatedBy())
            {
                $password->setCreatedBy($this->getUser());
            }
            
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
     * Update a Password. Only the creator may update an object.
     */
    public function updatePasswordAction(Request $request, $id)
    {
        $password = $this->getDoctrine()
            ->getRepository('TenKPwLockerBundle:Password')
            ->findOneBy(array('id' => $id, 
                'createdBy' => $this->getUser()->getId()));
        
        if (!$password) 
        {
            throw $this->createNotFoundException("404 NOT FOUND");
        }
        
        $response = $this->processForm($password, $request);
        
        if ($response === false)
        {
            $response = new Response(json_encode(array(
                'error' => 'Unable to update password')
            ), 500);
        }
        
        return $response;
    }
    
    /**
     * Delete a Password. Only the creator may delete an object.
     */
    public function deletePasswordAction(Request $request, $id)
    {
        $password = $this->getDoctrine()
            ->getRepository('TenKPwLockerBundle:Password')
            ->findOneBy(array('id' => $id, 
                'createdBy' => $this->getUser()->getId()));
        
        if (!$password) 
        {
            throw $this->createNotFoundException("404 NOT FOUND");
        }

        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($password);
        $em->flush();
        
        return new Response();
    }

    /**
     * Get a single Password
     */
    public function getPasswordAction($id)
    {
        $repository = $this->getDoctrine()
            ->getRepository('TenKPwLockerBundle:Password');
            
        $queryBuilder = $repository->createQueryBuilder('p')
            ->where('p.id = :id')
            ->setParameter('id', $id);
        
        $queryBuilder = $repository->userCanReadRestriction($queryBuilder, $this->getUser());
        
        try
        {
            $password = $queryBuilder->getQuery()
                ->getSingleResult();
        } 
        catch (\Doctrine\ORM\NoResultException $e)
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
        $repository = $this->getDoctrine()
            ->getRepository('TenKPwLockerBundle:Password');
        
        $queryBuilder = $repository->createQueryBuilder('p');
        $queryBuilder = $repository->userCanReadRestriction($queryBuilder, $this->getUser());
        
        $passwords = $queryBuilder->getQuery()
            ->getResult();
        
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
            $resource = new PasswordResource($password, $this->get('router'));
            $responseArray[] = $resource->toArray();
        }

        return $responseArray;
    }
}
