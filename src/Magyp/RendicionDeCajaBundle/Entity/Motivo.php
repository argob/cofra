<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Motivo por el cual se rechaza una rendicion, se adjunta a una Motivo
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Magyp\RendicionDeCajaBundle\Entity\MotivoRepository")
 */
class Motivo
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
     * @Assert\NotBlank(message="Campo Obligatorio")
     * @ORM\Column(name="contenido", type="string", length=1000)
     */
    private $contenido;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Notificacion $notificacion
     *
     * @ORM\OneToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Notificacion", inversedBy="motivo")
     * 
     */
    private $notificacion;
    


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
     * Set contenido
     *
     * @param string $contenido
     * @return Motivo
     */
    public function setContenido($contenido)
    {
        $this->contenido = $contenido;
    
        return $this;
    }

    /**
     * Get contenido
     *
     * @return string 
     */
    public function getContenido()
    {
        return $this->contenido;
    }

    public function getNotificacion() {
        return $this->notificacion;
    }

    public function setNotificacion(\Magyp\RendicionDeCajaBundle\Entity\Notificacion $notificacion) {
        $this->notificacion = $notificacion;
    }

    function __construct($texto = "") {
        $this->setContenido($texto);
    }


    
}
