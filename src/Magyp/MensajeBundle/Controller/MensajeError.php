<?php

namespace Magyp\MensajeBundle\Controller;


class MensajeError extends MensajeSession
{
    protected $errores;
    
    function __construct($request,$errores, $formulario = null) {
	$this->session = $request->getSession();
	$this->errores = $errores;
	if(!is_null($formulario))$this->errores = $this->getErrorMessages ($formulario);
	$this->generarMensaje();
	
    }
    
    public function getErrores(){
	return $this->errores;
    }
    public function generarMensaje(){
	    
	$contenido = "";
	$errores = $this->getErrores() ;
//	echo '<pre>';
//	var_dump($errores);
//	
//	echo '</pre>';
	$errores_campo = $this->getErrores();
	$contenido = "";
	foreach( $errores_campo as $campo_error => $contenido_error ){		
		$contenido.= "<li>";
		foreach( $contenido_error as $info){
		$contenido .= "<strong>".$campo_error .'</strong>: '. $info;
		}$contenido.= "</li>";
	}
//	echo '<pre>';
//	var_dump($contenido);
//	
//	echo '</pre>';
	$this->mensajeArray = array('titulo' => 'Error en Formulario', 'contenido' => $contenido,'template' =>'Error','icono' =>'error2.png');
	
    }
    
    public function getErrorMessages(\Symfony\Component\Form\Form $form) {      
	    $errors = array();

	    //if ($form->hasChildren()) {
                foreach ($form->all() as $child) {
		    if (!$child->isValid()) {
			$errors[$child->getName()] = $this->getErrorMessages($child);
		    }
		}
	    //} else {
		foreach ($form->getErrors() as $key => $error) {
		    $errors[] = $error->getMessage();
		}   
	    //}

	    return $errors;
    }
	
    public function hasErrores(){	
	  return count($this->errores) > 0;
      }  
}



?>