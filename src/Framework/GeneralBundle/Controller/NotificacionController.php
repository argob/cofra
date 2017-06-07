<?php
namespace Framework\GeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session;

/**
 * Description of NotificacionController
 * @author diosseba
 */
class NotificacionController{
	const NOTIFICACION_NOTIFICACION = 'notif';
	const NOTIFICACION_EXITO = 'exito';
	const NOTIFICACION_ERROR = 'error';
	
	public function mensajeNotificacion($mensaje, Session $sesion){
		$this->mensajeConfigurado($mensaje, $sesion,self::NOTIFICACION_NOTIFICACION);
	}
	
	public function mensajeExito($mensaje, Session $sesion){
		$this->mensajeConfigurado($mensaje, $sesion,self::NOTIFICACION_EXITO);
	}

	public function mensajeError($mensaje, Session $sesion){
		$this->mensajeConfigurado($mensaje, $sesion,self::NOTIFICACION_ERROR);
	}
	
	/**
	 * 
	 * @param String $mensaje
	 * @param \Symfony\Component\HttpFoundation\Session $sesion
	 * @param integer $tipo
	 * @param double $pos_x
	 * @param double $pos_y
	 * @param boolean $bloquea
	 */
	public function mensajeConfigurado($mensaje,Session $sesion, $tipo= self::NOTIFICACION_NOTIFICACION,$pos_x=null,$pos_y=null,$bloquea=true){
		$sesion->setFlash("notificacion_mensaje", $mensaje);
		$sesion->setFlash("notificacion_tipo", $tipo);
		if(is_null($pos_x))$pos_x="";
		if(is_null($pos_y))$pos_y="";
		$bloquea = ($bloquea)?'1':'0';
		$sesion->setFlash("notificacion_bloquea", $bloquea);
		$sesion->setFlash("notificacion_pos_x", $pos_x);
		$sesion->setFlash("notificacion_pos_y", $pos_y);
		/**
		 * @todo Agregar autohide
		 */
	}
}

?>