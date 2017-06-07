<?php

namespace Magyp\MensajeBundle\Controller;

use \Symfony\Component\HttpFoundation\Request;

class MensajeSession
{
    protected $session;
    protected $mensaje;
    protected $mensajeArray;
    protected $preferencias;
    
    function __construct(Request $request) {
	$this->session = $request->getSession();
	$preferencias = array();
	// si hay mensaje seteado lo devuelve. solo pasa cuando lo llama el evento.
	
    }
    
    public function setMensaje($titulo,$contenido,$estilo = null,$template = null,$icono = null){
	$this->mensajeArray = array('titulo' => $titulo, 'contenido' => $contenido);
	if(!is_null($estilo))$this->mensajeArray['estilo'] = $estilo;
	if(!is_null($template))$this->mensajeArray['template'] = $template;
	else $this->mensajeArray['template'] = 'mensajeCofra';
	if(!is_null($icono))$this->mensajeArray['icono'] = $icono;
	
	return $this;
    }
    public function getMensaje(){
	// aca modificar el mensaje segun preferencias.
	$mensaje = \unserialize($this->getSession()->get('mensaje'));
	//echo 'mensajession:'.$mensaje;
	$this->liberar(); // el mensaje se puede leer solo una vez. cuidado!! si se lanzan eventos del mismo tipo antes del correcto no se visualizara.
	return $mensaje;
    }
    public function setPreferencias($preferencias){
	
    }

    public function getSession(){
	return $this->session;
    }    
    public function liberar(){
	$this->getSession()->remove('mensaje');
	$this->getSession()->remove('preferencias');
    }    
    
    public function hasMensaje(){	
	if(!is_null($this->getSession()->get('mensaje'))) return true;
	return false;
    }    

    public function setIcono($icono){	
	// alert.png;
	$this->mensajeArray['icono'] = $icono;
	return $this;
    }    
    public function setTemplate($template){	
	$this->mensajeArray['template'] = $template;
	return $this;
    }    
    public function NoExpira(){	
	$this->preferencias['expires'] = 'false';
	return $this;
    }    
    public function Generar(){		
	if(count($this->preferencias)>0)$this->mensajeArray['opciones'] = $this->getPreferencias (); 
	$this->mensaje = \serialize($this->mensajeArray);
	$this->getSession()->set('mensaje',$this->mensaje);	
	//$this->getSession()->set('preferencias',$this->getPreferencias());
    }
    public function getPreferencias(){
	$prefrencias = "";
	foreach($this->preferencias as $opcion => $contenido){
	    $prefrencias .= $opcion .":".$contenido .",";
	}
	return $prefrencias;
    }
    public function Exito(){	
	$this->setTemplate('Exito')
	->setIcono('validado7.png');	
	return $this;
    }    
    public function Borrar(){	
	$this->setTemplate('Exito')
	->setIcono('cruz-roja2.png');	
	return $this;
    }    
    public function Nuevo(){	
	$this->setTemplate('Exito')
	->setIcono('cruz-verde.png');	
	return $this;
    }    
    public function Error(){	
	$this->setTemplate('Exito')
	->setIcono('cruz-roja.png');	
	return $this;
    }    
    public function Posision($posision){	
	switch($posision){
	    case 'central-top':
		$this->mensajeArray['posision'] = "'left':0, 'right':0, 'top':50";
	    break;
		$this->mensajeArray['posision'] = $posision;
	    default:
		
	}
	
	return $this;
    }    
    	    
		//->setIcono('error2.png')
		
}



?>