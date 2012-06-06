<?php

namespace TenK\PwLockerBundle\ApiResource;

use Symfony\Component\Routing\Router;
use TenK\PwLockerBundle\Entity\PasswordContact;
use TenK\PwLockerBundle\ApiResource\UserResource;

class PasswordContactResource 
{
    protected $instance;
    
    public function __construct(PasswordContact $object, Router $router)
    {
        $this->instance = $object;
        $this->router = $router;
    }
    
    /**
     * Proxy methods that haven't been overridden to the underlying entity
     */
    public function __call($methodName, $arguments)
    {
        if (method_exists($this->instance, $methodName))
        {
            return $this->instance->$methodName($arguments);
        }
    }
    
    /**
     * The fixed URI for this resource
     */
    public function getResourceUrl()
    {
        return $this->router->generate('TenKPwLockerBundle_password_contact_api_get', 
            array('id' => $this->instance->getId()), true);
    }
    
    /**
     * Returns the public representation of this object as an array
     * 
     * @return array
     */
    public function toArray()
    {
        $toUserResource = new UserResource($this->getToUser(), $this->router);
        $fromUserResource = new UserResource($this->getFromUser(), $this->router);
        
        return array(
            'id' => $this->getId(),
            'from_user' => $fromUserResource->toArray(),
            'to_user' => $toUserResource->toArray(),
            'url' => $this->getResourceUrl(),
        );
    }
}
