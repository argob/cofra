<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * FondoRotatorioFactura
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class FondoRotatorioFactura
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
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\FondoRotatorio", inversedBy="fondorotatoriofactura_id")
	 * @ORM\JoinColumn(name="fondorotatorio_id",referencedColumnName="id")
     */
    private $fondorotatorio;
    
     /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\TipoFactura $tipofactura
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\TipoFactura")
     */
    private $tipofactura;
    
    /**
     * @var integer
     * @Assert\NotBlank()
     * @ORM\Column(name="numero", type="integer")
     */
    private $numero;

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
     * @return FondoRotatorio
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
    
    
    public function getTipofactura() {
		return $this->tipofactura;
	}

	public function setTipofactura($tipofactura) {
		$this->tipofactura = $tipofactura;
	}
    

    /**
     * Set numero
     *
     * @param integer $numero
     * @return FondoRotatorioFactura
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    
        return $this;
    }

    /**
     * Get numero
     *
     * @return integer 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set monto
     *
     * @param float $monto
     * @return FondoRotatorioFactura
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
