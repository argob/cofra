<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Magyp\RendicionDeCajaBundle\Entity\Proveedor
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Magyp\RendicionDeCajaBundle\Entity\ProveedorRepository")
 */
class Proveedor
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
     * @var string $descripcion
     * @Assert\NotBlank()
     * @ORM\Column(name="descripcion", type="string", length=255)
     */
    private $descripcion;

    /**
    * @var bigint $cuit
    * @ORM\Column(name="cuit", type="bigint", nullable=false)
    * @Assert\NotBlank()
    */
    private $cuit;
    
    /**
     * @var integer $borrado
     * @ORM\Column(name="borrado", type="integer")
     */
    private $borrado;	

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\EventoProveedor[] $eventoproveedores
     * 
     * @ORM\OneToMany(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\EventoProveedor", mappedBy="proveedor")
     */
    private $eventoproveedores;	

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Area $area
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Area")
     * @ORM\JoinColumn(name="area_id",referencedColumnName="id")
     * 
     */
    private $area;
    
    
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return Proveedor
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
	
	function __toString() {
		return $this->descripcion;
	}

    /**
	 * Set CUIT
	 *
	 * @param bigint $cuit
	 */
	public function setCuit($cuit) {
		$this->cuit = $cuit;
	}

	/**
	 * Get CUIT
	 *
	 * @return bigint 
	 */
	public function getCuit() {
		return $this->cuit;
	}

    public function showCuit() {
		return substr($this->cuit, 0, 2) . "-" . substr($this->cuit, 2, 8) . "-" . substr($this->cuit, -1, 1);
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

        public function getEventoproveedores() {
            return $this->eventoproveedores;
        }

        public function setEventoproveedores(\Magyp\RendicionDeCajaBundle\Entity\EventoProveedor $eventoproveedores) {
            $this->eventoproveedores = $eventoproveedores;
        }

        public function esRestaurable($fecha){
            
            return true;
            //return rand(0, 1);;
        }
	public function getEventos(){
	    return $this->getEventoproveedores();
	}
        
        
    /**
     * Set area
     *
     * @param integer $area
     * @return Proveedor
     */
    public function setArea($area)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Get area
     *
     * @return Area
     */
    public function getArea()
    {
        return $this->area;
    }
        
}
