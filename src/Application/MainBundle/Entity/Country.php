<?php

namespace Application\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Country
 */
class Country
{
    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $lang_code;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $administrativeDivisions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->administrativeDivisions = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set code
     *
     * @param string $code
     * @return Country
     */
    public function setCode($code)
    {
        $this->code = $code;
    
        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Country
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
     * Set lang_code
     *
     * @param string $langCode
     * @return Country
     */
    public function setLangCode($langCode)
    {
        $this->lang_code = $langCode;
    
        return $this;
    }

    /**
     * Get lang_code
     *
     * @return string 
     */
    public function getLangCode()
    {
        return $this->lang_code;
    }

    /**
     * Add administrativeDivisions
     *
     * @param \Application\MainBundle\Entity\AdministrativeDivision $administrativeDivisions
     * @return Country
     */
    public function addAdministrativeDivision(\Application\MainBundle\Entity\AdministrativeDivision $administrativeDivisions)
    {
        $this->administrativeDivisions[] = $administrativeDivisions;
    
        return $this;
    }

    /**
     * Remove administrativeDivisions
     *
     * @param \Application\MainBundle\Entity\AdministrativeDivision $administrativeDivisions
     */
    public function removeAdministrativeDivision(\Application\MainBundle\Entity\AdministrativeDivision $administrativeDivisions)
    {
        $this->administrativeDivisions->removeElement($administrativeDivisions);
    }

    /**
     * Get administrativeDivisions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAdministrativeDivisions()
    {
        return $this->administrativeDivisions;
    }
}