<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * FondoRotatorioImputacion
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class FondoRotatorioImputacion
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
     * @var \Magyp\RendicionDeCajaBundle\Entity\FondoRotatorio $fondorotatorio
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\FondoRotatorio", inversedBy="fondorotatorioimputacion_id")
	 * @ORM\JoinColumn(name="fondorotatorio_id",referencedColumnName="id")
     */
    private $fondorotatorio;
    
    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Imputacion imputacion
     * @Assert\NotBlank()
	 * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Imputacion"	)
     * @ORM\JoinColumn(name="imputacion_id",referencedColumnName="id")
     */
    private $imputacion;
   

    /**
     * @var float
     * @Assert\NotBlank()
     * @ORM\Column(name="monto", type="decimal", precision=9, scale=2)
     */
    private $monto;


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
     * Set fondorotatorio
     *
     * @param integer $fondorotatorio
     * @return FondoRotatorioImputacion
     */
    public function setFondoRotatorio($fondorotatorio)
    {
        $this->fondorotatorio = $fondorotatorio;
    
        return $this;
    }

    
    /**
     * Get fondorotatorio
     *
     * @return FondoRotatorio 
     */
    public function getFondoRotatorio()
    {
        return $this->fondorotatorio;
    }
    
    
    /**
     * Set imputacion
     *
     * @param integer $imputacion
     * @return FondoRotatorioImputacion
     */
    public function setImputacion($imputacion)
    {
        $this->imputacion = $imputacion;
    
        return $this;
    }

    /**
     * Get imputacion
     *
     * @return Imputacion 
     */
    public function getImputacion()
    {
        return $this->imputacion;
    }


    /**
     * Set monto
     *
     * @param float $monto
     * @return FondoRotatorioImputacion
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
}
