<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * FondoRotatorio
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Magyp\RendicionDeCajaBundle\Entity\FondoRotatorioRepository")
 */
class FondoRotatorio
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
     * @var \Magyp\RendicionDeCajaBundle\Entity\FondoRotatorioFactura[] $fondorotatoriofactura  
     * 
     * @ORM\OneToMany(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\FondoRotatorioFactura", mappedBy="fondorotatorio_id")
     */
    private $fondorotatoriofactura;
    
    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\FondoRotatorioImputacion[] $fondorotatorioimputacion
     * 
     * @ORM\OneToMany(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\FondoRotatorioImputacion", mappedBy="fondorotatorio_id")
     */
    private $fondorotatorioimputacion;
    
    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="expediente", type="string", length=255)
     */
    private $expediente;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="nota", type="string", length=255, nullable=true)
     */
    private $nota;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Area $area
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Area")
	 * @ORM\JoinColumn(name="area_id",referencedColumnName="id")
     * 
     */
    private $area;

    /**
     * @var string
     *
     * @ORM\Column(name="beneficiario", type="string", length=255)
     */
    private $beneficiario;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="motivo", type="string", length=255)
     */
    private $motivo;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Programa $programa
     * @Assert\NotBlank()
	 * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Programa"	)
     * @ORM\JoinColumn(name="programa_id",referencedColumnName="id")
     */
    private $programa;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Actividad $actividad_id
     * @Assert\NotBlank()
	 * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Actividad"	)
     * @ORM\JoinColumn(name="actividad_id",referencedColumnName="id")
     */
    private $actividad;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\UbicacionGeografica ug_id
     * @Assert\NotBlank()
	 * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\UbicacionGeografica"	)
     * @ORM\JoinColumn(name="ug_id",referencedColumnName="id")
     */
    private $ug;
    
    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\FuenteFinanciamiento ff_id
     * @Assert\NotBlank()
	 * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\FuenteFinanciamiento"	)
     * @ORM\JoinColumn(name="ff_id",referencedColumnName="id")
     */
    
    private $fuentefinanciamiento;

    
     /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\FondoRotatorioRetencion[] $fondorotatorioretencion  
     * 
     * @ORM\OneToMany(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\FondoRotatorioRetencion", mappedBy="fondorotatorio_id")
     */
    private $fondorotatorioretencion;
    
    
     /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Usuario $responsable_id
     *
	 * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Usuario"	)
     * @ORM\JoinColumn(name="responsable_id",referencedColumnName="id")
     */
    private $responsable;
    
    
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
     * Set fondorotatoriofactura
     *
     * @param integer $fondorotatoriofactura
     * @return FondoRotatorio
     */
    public function setFondoRotatorioFactura($fondorotatoriofactura)
    {
        $this->fondorotatoriofactura = $fondorotatoriofactura;
    
        return $this;
    }

    /**
     * Get fondorotatoriofactura
     *
     * @return integer 
     */
    public function getFondoRotatorioFactura()
    {
        return $this->fondorotatoriofactura;
    }
    
    
    /**
     * Set fondorotatorioimputacion
     *
     * @param integer $fondorotatorioimputacion
     * @return FondoRotatorio
     */
    public function setFondoRotatorioImputacion($fondorotatorioimputacion)
    {
        $this->fondorotatorioimputacion = $fondorotatorioimputacion;
    
        return $this;
    }

    /**
     * Get fondorotatorioimputacion
     *
     * @return integer 
     */
    public function getFondoRotatorioImputacion()
    {
        return $this->fondorotatorioimputacion;
    }
    
    
    /**
     * Set expediente
     *
     * @param string $expediente
     * @return FondoRotatorio
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
     * @param string $nota
     * @return FondoRotatorio
     */
    public function setNota($nota)
    {
        $this->nota = $nota;
    
        return $this;
    }

    /**
     * Get nota
     *
     * @return string 
     */
    public function getNota()
    {
        return $this->nota;
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
     * Set beneficiario
     *
     * @param string $beneficiario
     * @return FondoRotatorio
     */
    public function setBeneficiario($beneficiario)
    {
        $this->beneficiario = $beneficiario;
    
        return $this;
    }

    /**
     * Get beneficiario
     *
     * @return string 
     */
    public function getBeneficiario()
    {
        return $this->beneficiario;
    }

    /**
     * Set motivo
     *
     * @param string $motivo
     * @return FondoRotatorio
     */
    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;
    
        return $this;
    }

    /**
     * Get motivo
     *
     * @return string 
     */
    public function getMotivo()
    {
        return $this->motivo;
    }

    /**
     * Set programa
     *
     * @param integer $programa
     * @return FondoRotatorio
     */
    public function setPrograma($programa)
    {
        $this->programa = $programa;
    
        return $this;
    }

    /**
     * Get programa
     *
     * @return Programa 
     */
    public function getPrograma()
    {
        return $this->programa;
    }


     /**
     * Set actividad
     *
     * @param integer $actividad
     * @return FondoRotatorio
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
     * Set ug
     *
     * @param UbicacionGeografica $ug
     * @return FondoRotatorio
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
     * Set fuentefinanciamiento
     *
     * @param integer $fuentefinanciamiento
     * @return FondoRotatorio
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
     * Set fondorotatorioretencion
     *
     * @param integer $fondorotatorioretencion
     * @return FondoRotatorio
     */
    public function setFondoRotatorioRetencion($fondorotatorioretencion)
    {
        $this->fondorotatorioretencion = $fondorotatorioretencion;
    
        return $this;
    }

    
    /**
     * Get fondorotatorioretencion
     *
     * @return FondoRotatorioRetencion 
     */
    public function getFondoRotatorioRetencion()
    {
        return $this->fondorotatorioretencion;
    }
    
     /**
     * Set responsable
     *
     * @param integer $responsable
     * @return FondoRotatorio
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
    
    function __toString(){
        return "";
    }
    
}
