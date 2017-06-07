<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FondoRotatorioRetencion
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class FondoRotatorioRetencion
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
     * @var \Magyp\RendicionDeCajaBundle\Entity\RetencionTipo retenciontipo
     *
	 * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\RetencionTipo"	)
     * @ORM\JoinColumn(name="retenciontipo_id",referencedColumnName="id")
     */
    private $retenciontipo;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Entity\FondoRotatorio $fondorotatorio
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\FondoRotatorio", inversedBy="fondorotatorioretencion_id")
	 * @ORM\JoinColumn(name="fondorotatorio_id",referencedColumnName="id")
     */
    private $fondorotatorio;

    /**
     * @var float
     *
     * @ORM\Column(name="monto", type="decimal", precision=9, scale=2)
     */
    private $monto;

    /**
     * Set retenciontipo
     *
     * @param RetencionTipo $retenciontipo 
     * @return FondoRotatorioFactura
     */
    public function setRetenciontipo($retenciontipo)
    {
        $this->retenciontipo = $retenciontipo;
        return $this;
    }

    /**
     * Get tipo
     *
     * @return RetencionTipo 
     */
    public function getRetenciontipo()
    {
        return $this->retenciontipo;
    }
    
    
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
     * @return FondoRotatorio
     */
    public function setFondorotatorio($fondorotatorio)
    {
        $this->fondorotatorio = $fondorotatorio;
    
        return $this;
    }

    /**
     * Get fondorotatoriofactura
     *
     * @return FondoRotatorio
     */
    public function getFondorotatorio()
    {
        return $this->fondorotatorio;
    }
    
    
    /**
     * Set monto
     *
     * @param float $monto
     * @return RetencionTipo
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
