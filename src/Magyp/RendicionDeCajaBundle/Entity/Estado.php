<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Magyp\MensajeBundle\Controller\MensajeSession;

/**
 * Estado
 *
 * 
 * @ORM\Entity
 */
class Estado
{
    const DESCONOCIDO = 0;
    const NUEVO = 1;
    const ENVIADO = 2;
    const ACEPTADO = 3;
    const ACORREGIR = 4;
    const ATESORERIA = 5;
    const ARCHIVADA= 6;
    const APAGAR = 7;
    const PAGADO= 8;
    
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
     * 
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;
    
    private $request;


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
     * Set nombre
     *
     * @param string $nombre
     * @return Estado
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    
        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }
    
    function __construct($request) {
	$this->request = $request;
    }

    public function mensajeEnviado()
    {
	$ms = new MensajeSession($this->request);
	$ms->setMensaje('Accion','La rendicion ha sido Enviada')
		->Exito()
		->Generar();
	
    }
    public function mensajeAceptado()
    {
	$ms = new MensajeSession($this->request);
	$ms->setMensaje('Accion','La rendicion ha sido Aceptada')
		->Exito()
		->Generar();
	
    }
    public function mensajeAcorregir()
    {
	$ms = new MensajeSession($this->request);
	$ms->setMensaje('Accion','La rendicion sera enviada para Corregir')
		->Exito()
		->Generar();
	
    }
    
    public function mensajeAtesoreria()
    {
	$ms = new MensajeSession($this->request);
	$ms->setMensaje('Accion','La rendicion sera enviada a tesoreria')
		->Exito()
		->Generar();
	
    }
            
    public function mensajeArchivada()
    {
	$ms = new MensajeSession($this->request);
	$ms->setMensaje('Accion','La rendicion fue archivada')
		->Exito()
		->Generar();
	
    }
    
        
    public function mensajeAapagar()
    {
	$ms = new MensajeSession($this->request);
	$ms->setMensaje('Accion','El responsable fue notificado.')
		->Exito()
		->Generar();
    }
    
    public function mensajeApagado()
    {
	$ms = new MensajeSession($this->request);
	$ms->setMensaje('Accion','La rendicion fue pagada..')
		->Exito()
		->Generar();
    }
    
    // cuando se cumple estado enviado se lanza este evento
    public function onEnviado(){
	// enviar notificacion a af con informacion de la rendicion
    }
    
    public function onAceptado(){
	// enviar notificacion a el usuario que su rendicion fue aceptada sin errores.
    }
    public function onAcorregir(){
	// enviar notificacion a el usuario que su rendicion ya esta habilitada para ser corregida
    }
    
    public function onAtesoreria(){
	// enviar notificacion a el usuario que su rendicion fue enviada a tesoreria
    }

    public function onArchivada(){
	// enviar notificacion a el usuario que su rendicion fue enviada a tesoreria
    }
    
    public function onAapagar(){
	// enviar notificacion a el usuario que su rendicion fue enviada a tesoreria
    }
    
    public function onApagado(){
	// enviar notificacion a el usuario que su rendicion fue enviada a tesoreria
    }
  
}
