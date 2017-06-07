<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Archivar
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Magyp\RendicionDeCajaBundle\Entity\ArchivarRepository")
 */
class Archivar
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
     * @var \Magyp\RendicionDeCajaBundle\Entity\Rendicion $rendicion
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Rendicion")
     * @ORM\JoinColumn(name="rendicion_id",referencedColumnName="id")
     * 
     */
    private $rendicion;

    
    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Liquidacion $liquidacion
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Liquidacion")
     * @ORM\JoinColumn(name="liquidacion_id",referencedColumnName="id")
     */
    private $liquidacion;

    /**
     * @var string
     *
     * @ORM\Column(name="caja", type="string", length=255, nullable=true)
     */
    private $caja;


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
     * Set rendicion
     *
     * @param integer $rendicion
     * @return Archivar
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
     * Set liquidacion
     *
     * @param integer $liquidacion
     * @return Archivar
     */
    public function setLiquidacion($liquidacion)
    {
        $this->liquidacion = $liquidacion;
    
        return $this;
    }

    /**
     * Get liquidacion
     *
     * @return integer 
     */
    public function getLiquidacion()
    {
        return $this->liquidacion;
    }

    /**
     * Set caja
     *
     * @param string $caja
     * @return Archivar
     */
    public function setCaja($caja)
    {
        $this->caja = $caja;
    
        return $this;
    }

    /**
     * Get caja
     *
     * @return string 
     */
    public function getCaja()
    {
        return $this->caja;
    }
}
