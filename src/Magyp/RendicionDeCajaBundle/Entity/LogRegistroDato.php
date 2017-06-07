<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LogRegistroDato
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Magyp\RendicionDeCajaBundle\Entity\LogRegistroDatoRepository")
 */
class LogRegistroDato extends ModificacionItem
{
//    /**
//     * @var integer
//     *
//     * @ORM\Column(name="id", type="integer")
//     * @ORM\Id
//     * @ORM\GeneratedValue(strategy="AUTO")
//     */
//    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="anterior", type="string", length=255, nullable=true)
     */
    private $anterior;

    /**
     * @var string
     *
     * @ORM\Column(name="nuevo", type="string", length=255)
     */
    private $nuevo;


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
     * Set anterior
     *
     * @param string $anterior
     * @return LogRegistroDato
     */
    public function setAnterior($anterior)
    {
        $this->anterior = $anterior;
    
        return $this;
    }

    /**
     * Get anterior
     *
     * @return string 
     */
    public function getAnterior()
    {
        return $this->anterior;
    }

    /**
     * Set nuevo
     *
     * @param string $nuevo
     * @return LogRegistroDato
     */
    public function setNuevo($nuevo)
    {
        $this->nuevo = $nuevo;
    
        return $this;
    }

    /**
     * Get nuevo
     *
     * @return string 
     */
    public function getNuevo()
    {
        return $this->nuevo;
    }
	
	function __construct($evento, $campo, $anterior, $nuevo) {
		
		$this->setCampo($campo);
		$this->setAnterior($anterior);
		$this->setNuevo($nuevo);
		$this->setEvento($evento);
	}
}
