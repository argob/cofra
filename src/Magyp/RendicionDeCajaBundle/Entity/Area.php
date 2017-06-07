<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Magyp\RendicionDeCajaBundle\Entity\Area
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Magyp\RendicionDeCajaBundle\Entity\AreaRepository")
 */
class Area {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $nombre
     * @Assert\NotBlank()
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Usuario $responsable
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Usuario")
     * @ORM\JoinColumn(name="responsable_id",referencedColumnName="id")
     * 
     */
    private $responsable;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Usuario $subresponsable
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Usuario"	)
     * @ORM\JoinColumn(name="subresponsable_id",referencedColumnName="id")
     */
    private $subresponsable;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Programa $programa
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Programa")
     */
    private $programa;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Actividad $actividad
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Actividad")
     */
    private $actividad;

    /**
     * @var integer $monto
     * @Assert\NotBlank()
     * @ORM\Column(name="monto", type="integer")
     */
    private $monto;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Programa $ff
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\FuenteFinanciamiento")
     */
    private $ff;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Programa $ug
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\UbicacionGeografica")
     */
    private $ug;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Rendicion[] $rendiciones
     * 
     * @ORM\OneToMany(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Rendicion", mappedBy="area")
     */
    private $rendiciones;

    /**
     * @var integer $borrado
     * @ORM\Column(name="borrado", type="integer")
     */
    private $borrado;


    /**
     * Get id
     * 
     * @return integer 
     */
    public function getId() {

        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Area
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Set responsable
     *
     * @param \Magyp\RendicionDeCajaBundle\Entity\Usuario $responsable
     * @return Area
     */
    public function setResponsable($responsable) {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * Get responsable
     *
     * @return \Magyp\RendicionDeCajaBundle\Entity\Usuario
     */
    public function getResponsable() {
        return is_null($this->responsable) ? "No tiene" : $this->responsable;
    }

    /**
     * Get boolean
     *
     * @return \Magyp\RendicionDeCajaBundle\Entity\Usuario
     */
    public function getTieneResponsable() {
        return is_null($this->responsable) ? false : true;
    }

    /**
     * Set subresponsable
     *
     * @param \Magyp\RendicionDeCajaBundle\Entity\Usuario $subresponsable
     * @return Area
     */
    public function setSubresponsable($subresponsable) {
        $this->subresponsable = $subresponsable;

        return $this;
    }

    /**
     * Get subresponsable
     *
     * @return \Magyp\RendicionDeCajaBundle\Entity\Usuario
     */
    public function getSubresponsable() {
        return is_null($this->subresponsable) ? "No tiene" : $this->subresponsable;
    }

    /** Get actividad
     * 	
     * @return type Actividad
     */
    public function getActividad() {
        return $this->actividad;
    }

    /** 	set Actividad
     *
     * @param type $actividad 
     */
    public function setActividad($actividad) {
        $this->actividad = $actividad;
    }

    /** Get programa
     * 	
     * @return type Programa
     */
    public function getPrograma() {
        return $this->programa;
    }

    /** 	set Programa
     *
     * @param type $programa 
     */
    public function setPrograma($programa) {
        $this->programa = $programa;
    }

    /** 	set UbicacionGeografica
     *
     * @param type $ug 
     */
    public function setUg($ug) {
        $this->ug = $ug;
    }

    /** Get UbicacionGeografica
     * 	
     * @return type UbicacionGeografica
     */
    public function getUg() {
        return $this->ug;
    }

    /** 	set FuenteFinanciamiento
     *
     * @param type $ff 
     */
    public function setFf($ff) {
        $this->ff = $ff;
    }

    /** Get FuenteFinanciamiento
     * 	
     * @return type FuenteFinanciamiento
     */
    public function getff() {
        return $this->ff;
    }

    function __construct($nombre = null, $id = null) {
        if (!is_null($id))
            $this->id = $id;
        if (!is_null($nombre))
            $this->setNombre($nombre);
    }

    function __toString() {
        return $this->getNombre();
    }

    public function getMonto() {
        return $this->monto;
    }

    public function setMonto($monto) {
        $this->monto = $monto;
    }

    public function getRendiciones() {
        return $this->rendiciones;
    }

    public function setRendiciones($rendiciones) {
        $this->rendiciones = $rendiciones;
    }

    public function hasResponsable() {
        return !is_null($this->responsable);
    }

    public function hasSubResponsable() {
        return !is_null($this->subresponsable);
    }

    function esAf() {
        return $this->getId() == 1000;
    }

    public function getBorrado() {
        return $this->borrado;
    }

    public function setBorrado($borrado) {
        $this->borrado = $borrado;
    }
    
    public function esTesoreria() {
        $pos = strpos($this->getNombre(), "tesoreria");
        if ($pos === false){
            $pos = strpos($this->getNombre(), "Tesoreria");
            if ($pos === false){
                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }
    }
}
