<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModificacionItem
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Magyp\RendicionDeCajaBundle\Entity\ModificacionItemRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="tipo",type="integer")
 * @ORM\DiscriminatorMap({"1"="LogRegistroDato","2"="LogRegistroEntidad"}) 
 * 
 */
abstract class ModificacionItem
{
//	const STRING = 1;
//	const INTEGER = 2;
//	const COMPROBANTE = 11;
//	const IMPUTACION = 12;
//	const PROVEEDOR = 13;
//	const RENDICION = 14;
//	
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="campo", type="string", length=255)
     */
    private $campo;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Evento $evento
     * 
	 * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Evento", inversedBy="listaDeCambios")
	 * ORM\JoinColumn(name="evento_id", referencedColumnName="id")
     */
    private $evento;
	

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
     * Set campo
     *
     * @param string $campo
     * @return ModificacionItem
     */
    public function setCampo($campo)
    {
        $this->campo = $campo;
    
        return $this;
    }

    /**
     * Get campo
     *
     * @return string 
     */
    public function getCampo()
    {
        return $this->campo;
    }

	
	/**
     * Get evento
     *
     * @return Magyp\RendicionDeCajaBundle\Entity\Evento 
     */
	public function getEvento() {
		return $this->evento;
	}

	/**
     * Set evento
     *
     * @param Magyp\RendicionDeCajaBundle\Entity\Evento $evento
     * @return Magyp\RendicionDeCajaBundle\Entity\Evento
     */
	public function setEvento($evento) {
		$this->evento = $evento;
	}

	public function getSoyEntidad() {
		return false;
	}
}
