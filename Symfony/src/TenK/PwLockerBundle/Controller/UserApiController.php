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
use TenK\PwLockerBundle\ApiResource\UserResource;

class UserApiController extends Controller
{
    /**
     * Returns the currently authenticated user.
     */
    protected function getUser()
    {
        return $this->get('security.context')->getToken()->getUser();
    }
    
    /**
     * Search for a user by username
     */
    public function findUserAction($username)
    {
        $user = $this->getDoctrine()
            ->getRepository('TenKUserBundle:User')
            ->findOneByUsername($username);
        
        // don't let users search for themselves.
        if ($this->getUser() == $user)
        {
            return new Response(null, 403);
        }
        
        if (!$user) 
        {
            throw $this->createNotFoundException("404 NOT FOUND");
        }
        
        $resource = new UserResource($user, $this->get('router'));
        
        return new Response(json_encode($resource->toArray()));
    }
}
