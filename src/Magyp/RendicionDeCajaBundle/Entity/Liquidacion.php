<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Framework\GeneralBundle\Entity\CodigoDeBarras;
use Doctrine\ORM\Mapping as ORM;

/**
 * Liquidacion
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Magyp\RendicionDeCajaBundle\Entity\LiquidacionRepository")
 */
class Liquidacion
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
     * @var \Magyp\RendicionDeCajaBundle\Entity\LiquidacionDetalle[] $liquidaciondetalle  
     * 
     * @ORM\OneToMany(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\LiquidacionDetalle", mappedBy="liquidacion_id")
     */
    private $liquidaciondetalle;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Rendicion $rendicion
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Rendicion")
	 * @ORM\JoinColumn(name="rendicion_id",referencedColumnName="id")
     * 
     */
    private $rendicion;

    /**
     * @var string
     *
     * @ORM\Column(name="expediente", type="string", length=255)
     */
    private $expediente;

    /**
     * @var string
     *
     * @ORM\Column(name="nota", type="string", length=255)
     */
    private $nota;

    /**
     * @var string
     *
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Area $area
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Area")
	 * @ORM\JoinColumn(name="area_id",referencedColumnName="id")
     * 
     */
    private $area;

     /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Usuario $responsable_id
     *
	 * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Usuario"	)
     * @ORM\JoinColumn(name="responsable_id",referencedColumnName="id")
     */
    private $responsable;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="periodoinicial", type="date")
     */
    private $periodoinicial;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="periodofinal", type="date")
     */
    private $periodofinal;
    
     /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Actividad $actividad_id
     *
	 * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Actividad"	)
     * @ORM\JoinColumn(name="actividad_id",referencedColumnName="id")
     */
    private $actividad;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="fuentefinanciamiento", type="integer")
     */
    private $fuentefinanciamiento;

     /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\UbicacionGeografica ug_id
     *
	 * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\UbicacionGeografica"	)
     * @ORM\JoinColumn(name="ug_id",referencedColumnName="id")
     */
    private $ug;
    
    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Usuario $beneficiario_id
     *
	 * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Usuario"	)
     * @ORM\JoinColumn(name="beneficiario_id",referencedColumnName="id")
     */
    private $beneficiario;

    /**
     * @var integer
     *
     * @ORM\Column(name="borrado", type="integer")
     */
    private $borrado;

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
     * Set liquidaciondetalle
     *
     * @param integer $liquidaciondetalle
     * @return Liquidacion
     */
    public function setLiquidaciondetalle($liquidaciondetalle)
    {
        $this->liquidaciondetalle = $liquidaciondetalle;
    
        return $this;
    }

    /**
     * Get liquidaciondetalle
     *
     * @return integer 
     */
    public function getLiquidaciondetalle()
    {
        return $this->liquidaciondetalle;
    }

    /**
     * Set rendicion
     *
     * @param integer $rendicion
     * @return Liquidacion
     */
    public function setRendicion($rendicion)
    {
        $this->rendicion = $rendicion;
    
        return $this;
    }

    /**
     * Get rendicion
     *
     * @return integer 
     */
    public function getRendicion()
    {
        return $this->rendicion;
    }

    /**
     * Set expediente
     *
     * @param string $expediente
     * @return Liquidacion
     */
    public function setExpediente($expediente)
    {
        $this->expediente = $expediente;
    
        return $this;
    }

    /**
     * Get expediente
     *
     * @return string 
     */
    public function getExpediente()
    {
        return $this->expediente;
    }

    /**
     * Set nota
     *
     * @param integer $nota
     * @return Liquidacion
     */
    public function setNota($nota)
    {
        $this->nota = $nota;
    
        return $this;
    }

    /**
     * Get nota
     *
     * @return integer 
     */
    public function getNota()
    {
        return $this->nota;
    }

    
     /**
     * Set fecha
     *
     * @param string $fecha
     * @return Liquidacion
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    
        return $this;
    }

    /**
     * Get fecha
     *
     * @return string
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    
    
    /**
     * Set area
     *
     * @param integer $area
     * @return Liquidacion
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

    /**
     * Set responsable
     *
     * @param integer $responsable
     * @return Liquidacion
     */
    public function setResponsable($responsable)
    {
        $this->responsable = $responsable;
    
        return $this;
    }

    /**
     * Get responsable
     *
     * @return Responsable 
     */
    public function getResponsable()
    {
        return $this->responsable;
    }

    /**
     * Set periodoinicial
     *
     * @param \DateTime $periodoinicial
     * @return Liquidacion
     */
    public function setPeriodoinicial($periodoinicial)
    {
        $this->periodoinicial = $periodoinicial;
    
        return $this;
    }

    /**
     * Get periodoinicial
     *
     * @return \DateTime 
     */
    public function getPeriodoinicial()
    {
        return $this->periodoinicial;
    }

    /**
     * Set periodofinal
     *
     * @param \DateTime $periodofinal
     * @return Liquidacion
     */
    public function setPeriodofinal($periodofinal)
    {
        $this->periodofinal = $periodofinal;
    
        return $this;
    }

    /**
     * Get periodofinal
     *
     * @return \DateTime 
     */
    public function getPeriodofinal()
    {
        return $this->periodofinal;
    }

    /**
     * Set actividad
     *
     * @param integer $actividad
     * @return Liquidacion
     */
    public function setActividad($actividad)
    {
        $this->actividad = $actividad;
    
        return $this;
    }

    /**
     * Get actividad
     *
     * @return Actividad 
     */
    public function getActividad()
    {
        return $this->actividad;
    }

    
    
    /**
     * Set fuentefinanciamiento
     *
     * @param integer $fuentefinanciamiento
     * @return Liquidacion
     */
    public function setFuentefinanciamiento($fuentefinanciamiento)
    {
        $this->fuentefinanciamiento = $fuentefinanciamiento;
    
        return $this;
    }

    /**
     * Get fuentefinanciamiento
     *
     * @return integer
     */
    public function getFuentefinanciamiento()
    {
        return $this->fuentefinanciamiento;
    }

    /**
     * Set ug
     *
     * @param UbicacionGeografica $ug
     * @return Liquidacion
     */
    public function setUg($ug)
    {
        $this->ug = $ug;
    
        return $this;
    }

    /**
     * Get ug
     *
     * @return UbicacionGeografica 
     */
    public function getUg()
    {
        return $this->ug;
    }
    
    /**
     * Set beneficiario
     *
     * @param Usuario $beneficiario
     * @return Liquidacion
     */
    public function setBeneficiario($beneficiario)
    {
        $this->beneficiario = $beneficiario;
    
        return $this;
    }

    /**
     * Get beneficiario
     *
     * @return Usuario 
     */
    public function getBeneficiario()
    {
        return $this->beneficiario;
    }
    
    function __toString() {	
        return (string)"liquidacion";
    }
    
    public function getBorrado() {
	return $this->borrado;
    }

    public function setBorrado($borrado) {
	$this->borrado = $borrado;
    }

    function __construct() {
	$this->setBorrado(0);
        $this->setFecha(new \DateTime());
    }
    
    /**
     * Get numeric
     *
     * @return Usuario 
     */
    public function getCodigoDeBarras()
    {
        $codigoDeBarras= new codigodebarras();
        return $codigoDeBarras->getCodigoDeBarras( 5, $this->getId());
    }

}
