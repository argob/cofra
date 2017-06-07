<?php

namespace Magyp\RendicionDeCajaBundle\Kernel;

use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine; 
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Mapping as ORM;
use Magyp\MensajeBundle\Controller\MensajeSession;
use Magyp\RendicionDeCajaBundle\Entity\Notificacion;
use Magyp\RendicionDeCajaBundle\Entity;

class Configuracion
{
	private $config;
	private $rand;	
		
	function __construct($config) {
		//var_dump($config);
		$this->setRand(rand(1, 1000));		
		$this->config = $config;
		
	}
	
	public function getConfig() {
		return $this->config;
	}

	public function setConfig($config) {
		$this->config = $config;
	}


	public function getRand() {
		return $this->rand;
	}

	public function setRand($rand) {
		$this->rand = $rand;
	}

	/** 
	 *	Session
	 * 
	 */
	public function permiteMultipleSession(){
		return $this->config['multiple_session'];				
	}
	public function avisarAlMailporMultipleSession(){
		
		if ($this->config['multiple_session'] == false)
			return $this->config['multiple_session'];				
		else 
			return false;
	}

	/**
	 *  Login Error
	 */
	
	public function cantidadIntentosAntesDeBloquear(){
		return $this->config['bloqueo_intentos'];
	}
	
	public function AvisarAlMailAlBloquear(){
		return $this->config['aviso_mail_bloqueo'];
	}
	
	public function cantidadErroresdeLogueoPermitidos(){
		return $this->config['logueo_fallido_aviso'];
	}
	
	public function AvisarAlMailSiSuperaIntentosPermitidos(){
		return $this->config['aviso_mail_intentos_fallidos'];
	}
	
}
