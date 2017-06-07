<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ReintegroDeGasto
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Magyp\RendicionDeCajaBundle\Entity\ReintegroDeGastoRepository")
 */
class ReintegroDeGasto
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
     * @var \Magyp\RendicionDeCajaBundle\Entity\ReintegroDeGastoDetalle[] $reintegrodegastodetalle  
     * 
     * @ORM\OneToMany(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\ReintegroDeGasto", mappedBy="reintegrodegasto_id")
     */
    private $reintegrodegastodetalle;

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
     * @var \DateTime
     * @Assert\NotBlank()
     * @ORM\Column(name="periodoinicial", type="date")
     */
    private $periodoinicial;

    /**
     * @var \DateTime
     * @Assert\NotBlank()
     * @ORM\Column(name="periodofinal", type="date")
     */
    private $periodofinal;

     /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Actividad $actividad_id
     * @Assert\NotBlank()
	 * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Actividad"	)
     * @ORM\JoinColumn(name="actividad_id",referencedColumnName="id")
     */
    private $actividad;
    
    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\FuenteFinanciamiento ff_id
     * @Assert\NotBlank()
	 * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\FuenteFinanciamiento"	)
     * @ORM\JoinColumn(name="ff_id",referencedColumnName="id")
     */
    
    private $fuentefinanciamiento;

     /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\UbicacionGeografica ug_id
     * @Assert\NotBlank()
	 * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\UbicacionGeografica"	)
     * @ORM\JoinColumn(name="ug_id",referencedColumnName="id")
     */
    private $ug;
    
    /**
     * @var string
     *
     * @ORM\Column(name="beneficiario", type="string", length=255)
     */
    private $beneficiario;

    
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
     * Set $reintegrodegastodetalle
     *
     * @param integer $reintegrodegastodetalle
     * @return ReintegroDeGasto
     */
    public function setReintegrodegastodetalle($reintegrodegastodetalle)
    {
        $this->reintegrodegastodetalle = $reintegrodegastodetalle;
    
        return $this;
    }

    /**
     * Get ReintegroDeGastoDetalle
     *
     * @return ReintegroDeGastoDetalle 
     */
    public function getReintegrodegastodetalle()
    {
        return $this->reintegrodegastodetalle;
    }

   
    /**
     * Set expediente
     *
     * @param string $expediente
     * @return ReintegroDeGasto
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
     * @return ReintegroDeGasto
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
     * Set area
     *
     * @param integer $area
     * @return ReintegroDeGasto
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
     * Set periodoinicial
     *
     * @param \DateTime $periodoinicial
     * @return ReintegroDeGasto
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
     * @return ReintegroDeGasto
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
     * @return ReintegroDeGasto
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
     * @return ReintegroDeGasto
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
     * @return ReintegroDeGasto
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
     * @return ReintegroDeGasto
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
    
     /**
     * Set responsable
     *
     * @param integer $responsable
     * @return ReintegroDeGasto
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
    
    
    
    function __toString() {	
        return (string)"reintegrodegasto";
    }


}
