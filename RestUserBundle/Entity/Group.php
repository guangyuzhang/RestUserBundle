<?php

namespace RestUserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="RestUserBundle\Repository\GroupRepository")
 * @ORM\Table(name="tbl_group")
 * @ExclusionPolicy("all")
 */
class Group
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Expose
     */
    protected $id;
    
    /**
     * @var string
     *
     * @ORM\Column(name="guid", type="string", length=36, nullable=false)
     * @Expose
     */
    protected $guid;
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Expose
     */
    protected $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     * @Expose
     */
    protected $description;
    
    /**
     * @var string
     *
     * @ORM\Column(name="roles", type="text", nullable=false)
     * @Expose
     */
    protected $roles;
    
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
     * Get guid
     *
     * @return string
     */
    public function getGuid()
    {
    	return $this->guid;
    }
    
    /**
     * Set guid
     *
     * @param string 
     *
     * @return Group
     */
    public function setGuid($guid)
    {
    	$this->guid = $guid;
    
    	return $this;
    }
    
    /**
     * Get name
     *
     * @return string
     */
	public function getName()
    {
    	return $this->name;
    }
    
    /**
     * Set name
     * 
     * @param string $name
     *
     * @return Group
     */
    public function setName($name)
    {
    	$this->name = $name;
    
    	return $this;
    }
    
    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
    	return $this->description;
    }
    
    /**
     * Set description
     *
     * @param string $description
     *
     * @return Group
     */
    public function setDescription($description)
    {
    	$this->description = $description;
    
    	return $this;
    }
    
    /**
     * Get roles
     *
     * @return string
     */
    public function getRoles()
    {
    	return $this->roles;
    }
    
    /**
     * @param string $roles
     *
     * @return Group
     */
    public function setRoles($roles)
    {
    	$this->roles = $roles;
    
    	return $this;
    }
    
    /**
     * @param string $role
     *
     * @return Group
     */
    public function addRole($role)
    {
    	$this->roles = trim($this->roles);
    	if(empty($this->roles)) {
    		$this->roles = strtoupper($role);
    	} else if (!$this->hasRole($role)) {
    		$this->roles += "," + strtoupper($role);
    	}
    
    	return $this;
    }
    
    /**
     * @param string $role
     *
     * @return boolean
     */
    public function hasRole($role)
    {
    	$rolesarray = explode(',', $this->roles);
    	return in_array(strtoupper($role), $rolesarray, true);
    }
    
    /**
     * @param string $role
     *
     * @return Group
     */
    public function removeRole($role)
    {
    	$rolesarray = explode(',', $this->roles);
    	if (false !== $key = array_search(strtoupper($role), $rolesarray, true)) {
    		unset($roles[$key]);
    		$this->roles = implode(',', array_values($rolesarray));
    	}
    
    	return $this;
    }
    
    
    
    
}
