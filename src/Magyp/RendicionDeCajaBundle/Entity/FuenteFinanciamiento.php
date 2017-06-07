<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FuenteFinanciamiento
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class FuenteFinanciamiento
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="ff_id", type="integer")
     */
    private $ff_id;


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
     * Set ff_id
     *
     * @param integer $ffId
     * @return FuenteFinanciamiento
     */
    public function setFfId($ffId)
    {
        $this->ff_id = $ffId;
    
        return $this;
    }

    /**
     * Get ff_id
     *
     * @return integer 
     */
    public function getFfId()
    {
        return $this->ff_id;
    }
    
    public function __toString() {
        return (string)$this->ff_id;
    }
}
