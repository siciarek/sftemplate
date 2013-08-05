<?php

namespace Application\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdministrativeDivision
 */
class AdministrativeDivision
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $places;

    /**
     * @var \Application\MainBundle\Entity\Country
     */
    private $country;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->places = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return AdministrativeDivision
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
     * Add places
     *
     * @param \Application\MainBundle\Entity\Place $places
     * @return AdministrativeDivision
     */
    public function addPlace(\Application\MainBundle\Entity\Place $places)
    {
        $this->places[] = $places;
    
        return $this;
    }

    /**
     * Remove places
     *
     * @param \Application\MainBundle\Entity\Place $places
     */
    public function removePlace(\Application\MainBundle\Entity\Place $places)
    {
        $this->places->removeElement($places);
    }

    /**
     * Get places
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlaces()
    {
        return $this->places;
    }

    /**
     * Set country
     *
     * @param \Application\MainBundle\Entity\Country $country
     * @return AdministrativeDivision
     */
    public function setCountry(\Application\MainBundle\Entity\Country $country)
    {
        $this->country = $country;
    
        return $this;
    }

    /**
     * Get country
     *
     * @return \Application\MainBundle\Entity\Country 
     */
    public function getCountry()
    {
        return $this->country;
    }
}