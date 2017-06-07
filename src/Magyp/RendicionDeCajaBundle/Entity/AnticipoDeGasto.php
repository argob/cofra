<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AnticipoDeGasto
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Magyp\RendicionDeCajaBundle\Entity\AnticipoDeGastoRepository")
 */
class AnticipoDeGasto
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
     * @var float
     * @Assert\NotBlank()
     * @ORM\Column(name="monto", type="decimal", precision=9, scale=2)
     */
    private $monto;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="motivo", type="string", length=255)
     */
    private $motivo;

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
     * Set expediente
     *
     * @param string $expediente
     * @return AnticipoDeGasto
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
     * @return AnticipoDeGasto
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
     * @return AnticipoDeGasto
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
     * Set monto
     *
     * @param float $monto
     * @return AnticipoDeGasto
     */
    public function setMonto($monto)
    {
        $this->monto = $monto;
    
        return $this;
    }

    /**
     * Get monto
     *
     * @return float 
     */
    public function getMonto()
    {
        return $this->monto;
    }

    /**
     * Set motivo
     *
     * @param string $motivo
     * @return AnticipoDeGasto
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

    
    
    
}
