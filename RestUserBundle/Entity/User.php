<?php

namespace RestUserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="RestUserBundle\Repository\UserRepository")
 * @ORM\Table(name="tbl_user")
 * @ExclusionPolicy("all")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    protected $id;

    /**
     * @var string $guid
     *
     * @ORM\Column(name="guid", type="string", length=36, nullable=false)
     * @Expose
     */
    protected $guid;
    
    /**
     * @var integer $groupdid
     *
     * @ORM\Column(name="group_id", type="integer", nullable=false)
     * @Expose
     */
    protected $groupid;
    
	/**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=false)
     * @Expose
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(name="username_canonical", type="string", length=255, nullable=false)
     * @Expose
     */
    protected $usernameCanonical;

    /**
     * @var string
     *
     * @ORM\Column(name="fullname", type="string", length=255, nullable=false)
     * @Expose
     */
    protected $fullname;
    
    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     * @Expose
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="email_canonical", type="string", length=255, nullable=false)
     * @Expose
     */
    protected $emailCanonical;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=45, nullable=false)
     * @Expose
     */
    protected $phone;
    
    /**
     * @var string
     *
     * @ORM\Column(name="department", type="string", length=255, nullable=false)
     * @Expose
     */
    protected $department;
    
    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=false)
     * @Expose
     */
    protected $salt;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     * @Expose
     */
    protected $password;

    /**
     * Plain password. Used for model validation. Must not be persisted.
     *
     * @var string
     */
    protected $plainPassword;

    /**
     * @var Group
     * 
     * @Expose
     */
    protected $group;
    
    /**
     * Returns the user unique id.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function getGroupId()
    {
        return $this->groupid;
    }

    public function setGroupId($groupid)
    {
    	$this->groupid = $groupid;
    
    	return $this;
    }
    
    public function getGuid()
    {
    	return $this->guid;
    }
    
    public function setGuid($guid)
    {
    	$this->guid = $guid;
    
    	return $this;
    }
    
    public function getUsername()
    {
    	return $this->username;
    }
    
    public function setUsername($username)
    {
    	$this->username = $username;
    
    	return $this;
    }

    public function getUsernameCanonical()
    {
        return $this->usernameCanonical;
    }
    
    public function setUsernameCanonical($usernameCanonical)
    {
    	$this->usernameCanonical = $usernameCanonical;
    
    	return $this;
    }
    

    public function getFullname()
    {
    	return $this->fullname;
    }
    
    public function setFullname($fullname)
    {
    	$this->fullname = $fullname;
    
    	return $this;
    }
    
    public function getEmail()
    {
        return $this->email;
    }
    
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getEmailCanonical()
    {
        return $this->emailCanonical;
    }

    public function setEmailCanonical($emailCanonical)
    {
    	$this->emailCanonical = $emailCanonical;
    
    	return $this;
    }
    
    public function getPhone()
    {
    	return $this->phone;
    }
    
    public function setPhone($phone)
    {
    	$this->phone = $phone;
    
    	return $this;
    }

    public function getDepartment()
    {
    	return $this->department;
    }
    
    public function setDepartment($department)
    {
    	$this->department = $department;
    
    	return $this;
    }
    
    public function getSalt()
    {
    	return $this->salt;
    }
    
	public function setSalt($salt)
    {
    	$this->salt = $salt;
    
    	return $this;
    }
    
    /**
     * Gets the encrypted password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;

        return $this;
    }
    
    public function eraseCredentials()
    {
    	$this->plainPassword = null;
    }
    
    /**
     * Get the group granted to the user.
     *
     * @return Group
     */
    public function getGroup()
    {
    	return $this->group;
    }
    
    public function setGroup($group)
    {
    	$this->group = $group;
    	
    	return $this;
    }
}
