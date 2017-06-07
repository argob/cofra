<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Referenciaimputacion
 *
 * @ORM\Table(name="ReferenciaImputacion")
 * @ORM\Entity(repositoryClass="Magyp\RendicionDeCajaBundle\Entity\ReferenciaimputacionRepository")
 * 
 */
class Referenciaimputacion
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo", type="string", length=45, nullable=true)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=45, nullable=true)
     */
    private $descripcion;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Imputacion $imputacion
     * 
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Imputacion")
     * @ORM\JoinColumn(name="imputacion_id", referencedColumnName="id")
     */
    private $imputacion;


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
     * Set codigo
     *
     * @param string $codigo
     * @return Referenciaimputacion
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    
        return $this;
    }

    /**
     * Get codigo
     *
     * @return string 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Referenciaimputacion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    
        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

	public function getImputacion() {
		return $this->imputacion;
	}

	public function setImputacion(\Magyp\RendicionDeCajaBundle\Entity\Imputacion $imputacion) {
		$this->imputacion = $imputacion;
	}


}