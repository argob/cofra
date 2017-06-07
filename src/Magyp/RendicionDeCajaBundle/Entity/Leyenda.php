<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Leyenda
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Magyp\RendicionDeCajaBundle\Entity\LeyendaRepository")
 */
class Leyenda
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
     * @ORM\Column(name="leyenda", type="string", length=255)
     */
    private $leyenda;

    /**
     * @var \DateTime
     * @Assert\NotBlank()
     * @ORM\Column(name="anio", type="date")
     */
    private $anio;

    /**
    * @var boolean $asignado
    *
    * @ORM\Column(name="asignado", type="boolean")
    */
   protected $asignado;

    /**
     * @var integer
     *
     * @ORM\Column(name="borrado", type="integer")
     */
    private $borrado;   

     /**
     * Get asignado
     *
     * @return boolean 
     */
    public function getAsignado()
    {
        return $this->asignado;
    }

    /**
     * Set asignado
     *
     * @param boolean $asignado
     * @return Leyenda
     */
    public function setAsignado($asignado)
    {
        $this->asignado = $asignado;
    
        return $this;
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
     * Set leyenda
     *
     * @param string $leyenda
     * @return Leyenda
     */
    public function setLeyenda($leyenda)
    {
        $this->leyenda = $leyenda;
    
        return $this;
    }

    /**
     * Get leyenda
     *
     * @return string 
     */
    public function getLeyenda()
    {
        return $this->leyenda;
    }

    /**
     * Set anio
     *
     * @param \DateTime $anio
     * @return Leyenda
     */
    public function setAnio($anio)
    {
        $this->anio = $anio;
    
        return $this;
    }

    /**
     * Get anio
     *
     * @return \DateTime 
     */
    public function getAnio()
    {
        return $this->anio;
    }
    
    public function getBorrado() {
	return $this->borrado;
    }

    public function setBorrado($borrado) {
	$this->borrado = $borrado;
    }

    function __construct() {
	$this->setBorrado(0);
    }

    
}
