<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventoUsuario
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Magyp\RendicionDeCajaBundle\Entity\EventoUsuarioRepository")
 */
class EventoUsuario extends Evento
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
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=255)
     */
    private $ip;

    /**
     * @var string
     *
     * @ORM\Column(name="equipo", type="string", length=255)
     */
    private $equipo;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Usuario $usuariodestino
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Usuario")
     * @ORM\JoinColumn(name="usuariodestino_id",referencedColumnName="id")
     */
    private $usuariodestino;
    
    


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
     * Set ip
     *
     * @param string $ip
     * @return EventoUsuario
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    
        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set equipo
     *
     * @param string $equipo
     * @return EventoUsuario
     */
    public function setEquipo($equipo)
    {
        $this->equipo = $equipo;
    
        return $this;
    }

    /**
     * Get equipo
     *
     * @return string 
     */
    public function getEquipo()
    {
        return $this->equipo;
    }
    
    function __construct($usuario,$tipoEvento, $usuariodestino = null,$usuariodestinoAnterior = null, $request = null) {
	$this->setUsuario($usuario);	    
	$this->setTipoEvento($tipoEvento);
	$this->setFecha(new \DateTime());
	$this->setIp($request->getClientIp());
	$this->setEquipo(''); // nombre magyp
	if(!is_null($usuariodestino))$this->setUsuariodestino($usuariodestino);
	$this->request = $request;
	if($tipoEvento == Evento::CAMBIOPASSWORD) $this->mensajeCambioPassword();
	if($tipoEvento == Evento::MODIFICIACION) $this->mensajeModificacion();
    }

    public function mensajeCambioPassword()
    {
	$mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->request);
	    $mensaje->setMensaje('Accion','Se cambio el password a ' .$this->getUsuariodestino())
	    ->Exito()
	    ->Generar();	

    }
    public function mensajeModificacion()
    {
	$mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->request);
	    $mensaje->setMensaje('Accion','Se actualizaron los datos de ' .$this->getUsuariodestino())
	    ->Exito()
	    ->Generar();	

    }
    
    public function getUsuariodestino() {
	return $this->usuariodestino->getUsername();
    }

    public function setUsuariodestino(\Magyp\RendicionDeCajaBundle\Entity\Usuario $usuariodestino) {
	$this->usuariodestino = $usuariodestino;
    }


}
