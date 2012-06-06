<?php

namespace TenK\PwLockerBundle\ApiResource;

use Symfony\Component\Routing\Router;

use TenK\PwLockerBundle\Entity\Password;

class PasswordResource 
{
    protected $password;
    
    public function __construct(Password $password, Router $router)
    {
        $this->password = $password;
        $this->router = $router;
    }
    
    /**
     * Proxy methods that haven't been overridden to the underlying entity
     */
    public function __call($methodName, $arguments)
    {
        if (method_exists($this->password, $methodName))
        {
            return $this->password->$methodName($arguments);
        }
    }
    
    /**
     * The fixed URI for this resource
     */
    public function getResourceUrl()
    {
        return $this->router->generate('TenKPwLockerBundle_password_api_get', 
            array('id' => $this->password->getId()), true);
    }
    
    /**
     * Returns a boolean to indicate whether the current user created this
     * object or not.
     * 
     * @todo implement this 
     * @return boolean 
     */
    public function getIsOwner()
    {
        return true;
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
            'password' => $this->getPassword(),
            'title' => $this->getTitle(),
            'url' => $this->getUrl(),
            'notes' => $this->getNotes(),
            'resource_url' => $this->getResourceUrl(),
            'is_owner' => $this->getIsOwner(),
            'shares' => $this->getShares()
        );
    }
}
