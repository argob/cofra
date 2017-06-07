<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Magyp\RendicionDeCajaBundle\Entity\Programa
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Programa
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
     * @var integer $programa_id
     *
     * @ORM\Column(name="programa_id", type="integer")
     */
    private $programa_id;


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
     * Set programa_id
     *
     * @param integer $programaId
     * @return Programa
     */
    public function setProgramaId($programaId)
    {
        $this->programa_id = $programaId;
    
        return $this;
    }

    /**
     * Get programa_id
     *
     * @return integer 
     */
    public function getProgramaId()
    {
        return $this->programa_id;
    }
    
    function __toString() {	
	return (string)$this->programa_id;
    }

}
