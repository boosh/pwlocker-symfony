<?php

namespace TenK\PwLockerBundle\ApiResource;

use Symfony\Component\Routing\Router;

use TenK\UserBundle\Entity\User;

class UserResource 
{
    protected $user;
    
    public function __construct(User $user, Router $router)
    {
        $this->user = $user;
        $this->router = $router;
    }
    
    /**
     * Proxy methods that haven't been overridden to the underlying entity
     */
    public function __call($methodName, $arguments)
    {
        if (method_exists($this->user, $methodName))
        {
            return $this->user->$methodName($arguments);
        }
    }
    
    /**
     * The fixed URI for this resource
     */
    public function getResourceUrl()
    {
        return $this->router->generate('TenKPwLockerBundle_user_api_get', 
            array('username' => $this->user->getUsername()), true);
    }
    
    /**
     * Returns the public representation of this object as an array
     * 
     * @return array
     */
    public function toArray()
    {
        return array(
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'url' => $this->getResourceUrl(),
        );
    }
}
