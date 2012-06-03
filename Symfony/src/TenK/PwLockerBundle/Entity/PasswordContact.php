<?php
namespace TenK\PwLockerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

use TenK\UserBundle\Entity\User;

/**
 * @ORM\Entity()
 * @ORM\Table(name="passwordcontact")
 * @ORM\HasLifecycleCallbacks()
 */
class PasswordContact
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="TenK\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="fromuser_id", referencedColumnName="id")
     */
    protected $fromUser;
    
    /**
     * @ORM\OneToOne(targetEntity="TenK\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="touser_id", referencedColumnName="id")
     */
    protected $toUser;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt; // = models.DateTimeField(auto_now_add=True, editable=False)
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt; // = models.DateTimeField(auto_now=True, editable=False)

    /** 
     * @ORM\ManyToMany(targetEntity="Password", mappedBy="passwordContacts") 
     */
    protected $passwords;
    
    public function __construct()
    {
        $this->passwords = new ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * Get createdAt
     *
     * @return datetime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function setUpdatedAtValue()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * Get updatedAt
     *
     * @return datetime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Add passwords
     *
     * @param TenK\PwLockerBundle\Entity\Password $passwords
     */
    public function addPassword(\TenK\PwLockerBundle\Entity\Password $passwords)
    {
        $this->passwords[] = $passwords;
    }

    /**
     * Get passwords
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPasswords()
    {
        return $this->passwords;
    }

    /**
     * Set fromUser
     *
     * @param TenK\UserBundle\Entity\User $fromUser
     */
    public function setFromUser(\TenK\UserBundle\Entity\User $fromUser)
    {
        $this->fromUser = $fromUser;
    }

    /**
     * Get fromUser
     *
     * @return TenK\UserBundle\Entity\User 
     */
    public function getFromUser()
    {
        return $this->fromUser;
    }

    /**
     * Set toUser
     *
     * @param TenK\UserBundle\Entity\User $toUser
     */
    public function setToUser(\TenK\UserBundle\Entity\User $toUser)
    {
        $this->toUser = $toUser;
    }

    /**
     * Get toUser
     *
     * @return TenK\UserBundle\Entity\User 
     */
    public function getToUser()
    {
        return $this->toUser;
    }
}