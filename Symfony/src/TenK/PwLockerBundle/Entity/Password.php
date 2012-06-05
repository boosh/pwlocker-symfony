<?php
namespace TenK\PwLockerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use TenK\UserBundle\Entity\User;

/**
 * @ORM\Entity(repositoryClass="TenK\PwLockerBundle\Repository\PasswordRepository")
 * @ORM\Table(name="password")
 * @ORM\HasLifecycleCallbacks()
 */
class Password
{
 //   protected $createdBy; // = models.ForeignKey(User, related_name='+', editable=False)
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="TenK\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     */
    protected $createdBy;
    
    /**
     * @ORM\Column(type="string", length=200)
     * @Assert\NotBlank();
     */
    protected $title; // = models.CharField(max_length=200)
    
    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    protected $username; // = models.CharField(max_length=200, blank=True)
    
    /**
     * @ORM\Column(type="string", length=200)
     * @Assert\NotBlank();
     */
    protected $password; // = models.CharField(max_length=200)
    
    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    protected $url; // = models.URLField(max_length=500, blank=True, verbose_name='Site URL')
    
    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    protected $notes; // = models.CharField( max_length=500, blank=True)
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt; // = models.DateTimeField(auto_now_add=True, editable=False)
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt; // = models.DateTimeField(auto_now=True, editable=False)

    /** 
     * @ORM\ManyToMany(targetEntity="PasswordContact", inversedBy="passwords") 
     */
    protected $shares;
    
    public function __construct()
    {
        $this->shares = new ArrayCollection();
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
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set username
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Return a masked password
     */
    public function getMaskedPassword()
    {
        return '*******';
    }
    
    /**
     * Set url
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set notes
     *
     * @param string $notes
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    /**
     * Get notes
     *
     * @return string 
     */
    public function getNotes()
    {
        return $this->notes;
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
     * Add shares
     *
     * @param TenK\PwLockerBundle\Entity\PasswordContact $shares
     */
    public function addPasswordContact(\TenK\PwLockerBundle\Entity\PasswordContact $shares)
    {
        $this->shares[] = $shares;
    }

    /**
     * Get shares
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getShares()
    {
        return $this->shares;
    }

    /**
     * Set createdBy
     *
     * @param TenK\UserBundle\Entity\User $createdBy
     */
    public function setCreatedBy(\TenK\UserBundle\Entity\User $createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * Get createdBy
     *
     * @return TenK\UserBundle\Entity\User 
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }
}