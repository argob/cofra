<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LiquidacionDetalle
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Magyp\RendicionDeCajaBundle\Entity\LiquidacionDetalleRepository")
 */
class LiquidacionDetalle
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
     * @var \Magyp\RendicionDeCajaBundle\Entity\Liquidacion $liquidacion
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Liquidacion", inversedBy="liquidaciondetalle_id")
	 * @ORM\JoinColumn(name="liquidacion_id",referencedColumnName="id")
     */
    private $liquidacion;

    
    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Programa $programa
     *
	 * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Programa"	)
     * @ORM\JoinColumn(name="programa_id",referencedColumnName="id")
     */
    private $programa;

    
     /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Imputacion imputacion
     *
	 * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Imputacion"	)
     * @ORM\JoinColumn(name="imputacion_id",referencedColumnName="id")
     */
    private $imputacion;

    /**
     * @var float
     *
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
     * Set liquidacion
     *
     * @param integer $liquidacion
     * @return LiquidacionDetalle
     */
    public function setLiquidacion($liquidacion)
    {
        $this->liquidacion = $liquidacion;
    
        return $this;
    }

    /**
     * Get liquidacion
     *
     * @return Liquidacion 
     */
    public function getLiquidacion()
    {
        return $this->liquidacion;
    }

    /**
     * Set programa
     *
     * @param integer $programa
     * @return LiquidacionDetalle
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
     * @return LiquidacionDetalle
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
     * @return LiquidacionDetalle
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
