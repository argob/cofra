<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ReintegroDeGastoDetalle
 * @ORM\Table()
 * @ORM\Entity
 */
class ReintegroDeGastoDetalle
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
     * @var \Magyp\RendicionDeCajaBundle\Entity\ReintegroDeGasto $reintegrodegasto
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\ReintegroDeGasto", inversedBy="reintegrodegastodetalle_id")
	 * @ORM\JoinColumn(name="reintegrodegasto_id",referencedColumnName="id")
     */
    private $reintegrodegasto;

    
    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Programa $programa
     * @Assert\NotBlank()
	 * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Programa"	)
     * @ORM\JoinColumn(name="programa_id",referencedColumnName="id")
     */
    private $programa;

    
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
     * @ORM\Column(name="importesubtotal", type="decimal", precision=9, scale=2)
     */
    private $importesubtotal;


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
     * Set $reintegrodegasto
     *
     * @param integer reintegrodegasto
     * @return ReintegroDeGastoDetalle
     */
    public function setReintegrodegasto($reintegrodegasto)
    {
        $this->reintegrodegasto = $reintegrodegasto;
    
        return $this;
    }

    /**
     * Get ReintegroDeGasto
     *
     * @return ReintegroDeGasto 
     */
    public function getReintegrodegasto()
    {
        return $this->reintegrodegasto;
    }

    /**
     * Set programa
     *
     * @param integer $programa
     * @return ReintegroDeGastoDetalle
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
     * Set imputacion
     *
     * @param integer $imputacion
     * @return ReintegroDeGastoDetalle
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
     * Set importesubtotal
     *
     * @param float $importesubtotal
     * @return ReintegroDeGastoDetalle
     */
    public function setImportesubtotal($importesubtotal)
    {
        $this->importesubtotal = $importesubtotal;
    
        return $this;
    }

    /**
     * Get importesubtotal
     *
     * @return float 
     */
    public function getImportesubtotal()
    {
        return $this->importesubtotal;
    }
}
