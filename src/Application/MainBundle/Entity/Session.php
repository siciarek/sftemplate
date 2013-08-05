<?php

namespace Application\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Session
 */
class Session
{
    /**
     * @var string
     */
    private $sess_id;

    /**
     * @var string
     */
    private $sess_data;

    /**
     * @var integer
     */
    private $sess_time;


    /**
     * Set sess_id
     *
     * @param string $sessId
     * @return Session
     */
    public function setSessId($sessId)
    {
        $this->sess_id = $sessId;
    
        return $this;
    }

    /**
     * Get sess_id
     *
     * @return string 
     */
    public function getSessId()
    {
        return $this->sess_id;
    }

    /**
     * Set sess_data
     *
     * @param string $sessData
     * @return Session
     */
    public function setSessData($sessData)
    {
        $this->sess_data = $sessData;
    
        return $this;
    }

    /**
     * Get sess_data
     *
     * @return string 
     */
    public function getSessData()
    {
        return $this->sess_data;
    }

    /**
     * Set sess_time
     *
     * @param integer $sessTime
     * @return Session
     */
    public function setSessTime($sessTime)
    {
        $this->sess_time = $sessTime;
    
        return $this;
    }

    /**
     * Get sess_time
     *
     * @return integer 
     */
    public function getSessTime()
    {
        return $this->sess_time;
    }
}