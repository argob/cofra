<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventoNotificacion
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Magyp\RendicionDeCajaBundle\Entity\EventoNotificacionRepository")
 */
class EventoNotificacion extends Evento
{
//    /**
//     * @var integer
//     *
//     * @ORM\Column(name="id", type="integer")
//     * @ORM\Id
//     * @ORM\GeneratedValue(strategy="AUTO")
//     */
//    private $id;
    /**
     * @var Magyp\RendicionDeCajaBundle\Entity\Notificacion $notificacion
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Notificacion")
     * @ORM\JoinColumn(name="notificacion_id",referencedColumnName="id")
     */
    private $notificacion;

    



    /**
     * Set notificacion
     *
     * @param Magyp\RendicionDeCajaBundle\Entity\Notificacion $notificacion
     * @return EventoNotificacion
     */
    public function setNotificacion($notificacion)
    {
        $this->notificacion= $notificacion;
    
        return $this;
    }

    /**
     * Get notificacion
     *
     * @return Notificacion Magyp\RendicionDeCajaBundle\Entity\Notificacion
     */
    public function getNotificacion()
    {
        return $this->notificacion;
    }
	
	function __construct($usuario, $tipoEvento, $notificacion, $notificacionAnterior = null, $request = null) {
		$this->setUsuario($usuario);
		$this->setFecha(new \DateTime());
		$this->setNotificacion($notificacion);
		$this->setTipoEvento($tipoEvento);
		if(!is_null($request))$this->request = $request;
	}
	
	
   
}
