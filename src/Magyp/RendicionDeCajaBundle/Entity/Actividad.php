<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Magyp\RendicionDeCajaBundle\Entity\Actividad
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Actividad
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer $actividad_id
     *
     * @ORM\Column(name="actividad_id", type="integer")
     */
    private $actividad_id;


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
     * Set actividad_id
     *
     * @param integer $actividadId
     * @return Actividad
     */
    public function setActividadId($actividadId)
    {
        $this->actividad_id = $actividadId;
    
        return $this;
    }

    /**
     * Get actividad_id
     *
     * @return integer 
     */
    public function getActividadId()
    {
        return $this->actividad_id;
    }
    
    function __toString() {	
	return (string)$this->actividad_id;
    }

}
