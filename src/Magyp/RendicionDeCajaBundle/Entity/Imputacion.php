<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Magyp\RendicionDeCajaBundle\Entity\Imputacion
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Magyp\RendicionDeCajaBundle\Entity\ImputacionRepository")
 */
class Imputacion
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
     * @var string $codigo
     * @Assert\NotBlank()
     * @ORM\Column(name="codigo", type="string", length=255)
     */
    private $codigo;

    /**
     * @var string $descripcion
     * @Assert\NotBlank()
     * @ORM\Column(name="descripcion", type="string", length=255)
     */
    private $descripcion;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Area $Area
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Area")
	 * @ORM\JoinColumn(name="area_id",referencedColumnName="id")
     * 
     */
    private $Area;
	
    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\ImputacionTipo $tipo
	 * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\ImputacionTipo")
     * @ORM\JoinColumn(name="tipo",referencedColumnName="id")
     */
    private $tipo;
	
	/**
     * @var integer $borrado
     * @ORM\Column(name="borrado", type="integer")
     */
    private $borrado;	

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\EventoImputacion[] $eventoimputaciones
     * 
     * @ORM\OneToMany(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\EventoImputacion", mappedBy="imputacion")
     */
    private $eventoimputaciones;	
    
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
     * Set codigo
     *
     * @param string $codigo
     * @return Imputacion
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    
        return $this;
    }

    /**
     * Get codigo
     *
     * @return string 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Imputacion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    
        return $this;
    }

    /**
     * Get descripcion
     * 
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set Area
     *
     * @param \Magyp\RendicionDeCajaBundle\Entity\Area $area
     * @return Imputacion
     */
    public function setArea($area)
    {
        $this->Area = $area;
    
        return $this;
    }

    /**
     * Get Area
     *
     * @return \Magyp\RendicionDeCajaBundle\Entity\Area
     */
    public function getArea()
    {
        return $this->Area;
    }
	function __toString() {
		//return $this->descripcion;
		return $this->getCodigo();
	}

	public function getTipo() {
		return $this->tipo;
	}

	public function setTipo($tipo) {
		$this->tipo = $tipo;
	}

       /**
     * Get tipo significado
     *
     * @return string 
     */
    public function getTipoSignificado()
    {
        return $this->tipo->getNombre();
    }
    
	public function getBorrado() {
		return $this->borrado;
	}

	public function setBorrado($borrado) {
		$this->borrado = $borrado;
	}

	function __construct() {
		$this->setBorrado(0);
	}
        public function getEventoimputaciones() {
            return $this->eventoimputaciones;
        }

        public function setEventoimputaciones(\Magyp\RendicionDeCajaBundle\Entity\EventoImputacion $eventoimputaciones) {
            $this->eventoimputaciones = $eventoimputaciones;
        }

        public function esRestaurable(){
        
	    return 1;
            return rand(0,1);
        }
	public function getEventos(){
	    return $this->getEventoimputaciones();
	}
}
