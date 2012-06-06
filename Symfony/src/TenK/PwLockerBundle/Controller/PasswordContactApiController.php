<?php

namespace TenK\PwLockerBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

use TenK\PwLockerBundle\Entity\PasswordContact;
use TenK\PwLockerBundle\Form\PasswordType;
use TenK\PwLockerBundle\Form\PasswordApiType;
use TenK\PwLockerBundle\ApiResource\PasswordContactResource;

class PasswordContactApiController extends Controller
{
    /**
     * Returns the currently authenticated user.
     */
    protected function getUser()
    {
        return $this->get('security.context')->getToken()->getUser();
    }
    
    /**
     * Create a new PasswordContact
     */
    public function createPasswordContactAction(Request $request)
    {
        $toUser = $this->getDoctrine()
            ->getRepository('TenKUserBundle:User')
            ->findOneById($request->get('to_user'));

        if (!$toUser)
        {
            throw $this->createNotFoundException("404 NOT FOUND");
        }
        
        if ($this->getUser() == $toUser)
        {
            return new Response(null, 403);
        }
        
        $passwordContact = new PasswordContact();
        $passwordContact->setFromUser($this->getUser());
        $passwordContact->setToUser($toUser);
        
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($passwordContact);
        $em->flush();
        
        $response = $this->getPasswordContactAction($request, $passwordContact->getId());
        $response->setStatusCode(201);
        return $response;
    }

    /**
     * Delete a Password. Only the creator may delete an object.
     */
    public function deletePasswordContactAction(Request $request, $id)
    {
        $passwordContact = $this->getDoctrine()
            ->getRepository('TenKPwLockerBundle:PasswordContact')
            ->findOneBy(array('id' => $id, 
                'fromUser' => $this->getUser()->getId()));
        
        if (!$passwordContact) 
        {
            throw $this->createNotFoundException("404 NOT FOUND");
        }

        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($passwordContact);
        $em->flush();
        
        return new Response(null, 204);
    }

    /**
     * Returns a single PasswordContact
     */
    public function getPasswordContactAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()
            ->getRepository('TenKPwLockerBundle:PasswordContact');
        
        $query = $repository->createQueryBuilder('p')
                ->where('p.id = :id')
                ->andWhere('p.fromUser = :fromUser')
                ->setParameters(array('id' => $id, 'fromUser' => $this->getUser()))
                ->getQuery();
        
        $contact = $query->getSingleResult();
        
        $contactResource = new PasswordContactResource($contact, $this->get('router'));
        
        return new Response(json_encode($contactResource->toArray()));
    }
    
    /**
     * Returns a list of Passwords
     */
    public function listPasswordContactsAction(Request $request)
    {
        $repository = $this->getDoctrine()
            ->getRepository('TenKPwLockerBundle:PasswordContact');
        
        $query = $repository->createQueryBuilder('p')
                ->where('p.fromUser = :fromUser')
                ->setParameter('fromUser', $this->getUser())
                ->getQuery();
        
        $contacts = $query->getResult();
        
        return new Response(json_encode($this->contactsToArray($contacts)));
    }
    
    /**
     * Convert PasswordContacts to an array. 
     */
    protected function contactsToArray($passwordContacts)
    {
        $responseArray = array();
        
        foreach ($passwordContacts as $contact)
        {
            $contact = new PasswordContactResource($contact, $this->get('router'));
            $responseArray[] = $contact->toArray();
        }

        return $responseArray;
    }
}
