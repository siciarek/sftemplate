<?php

namespace Application\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Place
 */
class Place
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $info;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $addresses;

    /**
     * @var \Application\Sonata\UserBundle\Entity\User
     */
    private $created_by;

    /**
     * @var \Application\MainBundle\Entity\AdministrativeDivision
     */
    private $administrativeDivision;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->addresses = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Place
     */
    public function setName($name)
    {
        $this->name = $name;
    
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
     * Set info
     *
     * @param string $info
     * @return Place
     */
    public function setInfo($info)
    {
        $this->info = $info;
    
        return $this;
    }

    /**
     * Get info
     *
     * @return string 
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Add addresses
     *
     * @param \Application\MainBundle\Entity\Address $addresses
     * @return Place
     */
    public function addAddresse(\Application\MainBundle\Entity\Address $addresses)
    {
        $this->addresses[] = $addresses;
    
        return $this;
    }

    /**
     * Remove addresses
     *
     * @param \Application\MainBundle\Entity\Address $addresses
     */
    public function removeAddresse(\Application\MainBundle\Entity\Address $addresses)
    {
        $this->addresses->removeElement($addresses);
    }

    /**
     * Get addresses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * Set created_by
     *
     * @param \Application\Sonata\UserBundle\Entity\User $createdBy
     * @return Place
     */
    public function setCreatedBy(\Application\Sonata\UserBundle\Entity\User $createdBy = null)
    {
        $this->created_by = $createdBy;
    
        return $this;
    }

    /**
     * Get created_by
     *
     * @return \Application\Sonata\UserBundle\Entity\User 
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }

    /**
     * Set administrativeDivision
     *
     * @param \Application\MainBundle\Entity\AdministrativeDivision $administrativeDivision
     * @return Place
     */
    public function setAdministrativeDivision(\Application\MainBundle\Entity\AdministrativeDivision $administrativeDivision)
    {
        $this->administrativeDivision = $administrativeDivision;
    
        return $this;
    }

    /**
     * Get administrativeDivision
     *
     * @return \Application\MainBundle\Entity\AdministrativeDivision 
     */
    public function getAdministrativeDivision()
    {
        return $this->administrativeDivision;
    }
}