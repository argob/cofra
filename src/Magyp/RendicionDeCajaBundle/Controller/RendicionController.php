<?php

namespace Magyp\RendicionDeCajaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\Rendicion;
use Magyp\RendicionDeCajaBundle\Form\RendicionType;
use Knp\Bundle\SnappyBundle\KnpSnappyBundle;

use Magyp\RendicionDeCajaBundle\Entity\Estado;


use JMS\SecurityExtraBundle\Annotation\Secure;

use Magyp\RendicionDeCajaBundle\Entity\EventoComprobante;
use Magyp\RendicionDeCajaBundle\Entity\EventoRendicion;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Rendicion controller.
 *
 * @Route("/sistema/rendicion")
 */
class RendicionController extends BaseCofraController
{
    /**
     * Lists all Rendicion entities.
     *
     * @Secure(roles="ROLE_RENDICION")
     * @Route("/", name="sistema_rendicion")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
	
        //$this->getRequest()->getSession()->set('mensaje', 'hola');	
        $usuario = $this->getUsuario();
        $elusuarioesAF = $this->container->get('security.context')->isGranted('ROLE_AF');
        $rendiciones= null;        
        $texto = $this->getRequest()->get('rendicion_buscar');
		$rendiciones = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->RendicionesDelArea($usuario,$texto);

	return array(
            'rendiciones' => $rendiciones,
		    'area' => $usuario->getArea()
        );
    }

    /**
     * Finds and displays a Rendicion entity.
     *
     * @Route("/{id}/show", name="sistema_rendicion_show")
     * @Secure(roles="ROLE_RENDICION")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rendicion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Rendicion entity.
     *
     * @Secure(roles="ROLE_RENDICION")
     * @Route("/nueva", name="sistema_rendicion_nueva")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Rendicion();
        $form   = $this->createForm(new RendicionType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Rendicion entity.
     *
     * @Route("/create", name="sistema_rendicion_create")
     * @Secure(roles="ROLE_RENDICION")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:Rendicion:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Rendicion();
        $form = $this->createForm(new RendicionType(), $entity);
        $form->bind($request);
	
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
	    $usuario = $this->getUsuario();
        $em->refresh($usuario);
	    $entity->setResponsable($usuario);
	    $entity->setArea($usuario->getArea());
            $em->persist($entity);
//	    $eventoRendicion = new EventoRendicion($this->getUsuario(), EventoRendicion::NUEVO, $entity);		
//	    $em->persist($eventoRendicion);
		$this->getCofra()->addEventoRendicion(EventoRendicion::NUEVO, $entity);
            $em->flush();
				
            //return $this->redirect($this->generateUrl('sistema_rendicion', array('id' => $entity->getId())));
	    return $this->redirect($this->generateUrl('sistema_rendicion_crear_listo', array('idrendicion' => $entity->getId())));
			
        }else{            
            $this->getCofra()->crearMensajedeError("Error", "El formato del Expediente es invalido<p><strong>Ej: </strong>3000001/2013<p>");            
        }
	        
	    	    		
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }
	/**
     * 
     *
     * @Route("/crear/listo/{idrendicion}", name="sistema_rendicion_crear_listo")
     * @Template()
     */
    public function crearListoAction($idrendicion)
    {
		
        return array('idrendicion' => $idrendicion);
    }
	
    /**
     * Displays a form to edit an existing Rendicion entity.
     *
     * @Route("/{id}/edit", name="sistema_rendicion_edit")
     * @Secure(roles="ROLE_RENDICION")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rendicion entity.');
        }

        $editForm = $this->createForm(new RendicionType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Rendicion entity.
     *
     * @Route("/{id}/update", name="sistema_rendicion_update")
     * @Secure(roles="ROLE_RENDICION")
     * @Method("POST")
     * Template("MagypRendicionDeCajaBundle:Rendicion:edit.html.twig")
     * @Template("MagypRendicionDeCajaBundle:Rendicion:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        //var_dump($request->request->all());
        //die;
        $em = $this->getDoctrine()->getManager();
        $em->refresh($this->getUsuario());
        $entity = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->find($id);
		$rendicionAnterior = clone $entity;
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rendicion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new RendicionType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            if($entity->esModificable()) {
                $em->persist($entity);		
                $eventoRendicion = $this->getCofra()->addEventoRendicion(EventoRendicion::MODIFICIACION, $entity, $rendicionAnterior);

                $em->flush();
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                $mensaje->setMensaje('Actualizado','Rendicion Actualizada con Exito',null,'Exito')
                    //->setIcono('error2.png')
                    ->setIcono('validado7.png')
                    ->Generar();
            }
            //return $this->forward("MagypRendicionDeCajaBundle:Rendicion:detalleRendicion", array('idrendicion' => $id));
            return $this->redirect($this->generateUrl('sistema_rendicion_detalle', array('idrendicion' => $id)));
        }
	
	//echo $editForm->getErrorsAsString();
	
	$errores = $this->getErrorMessages($editForm);
	if(count($errores)>0){
	    $mensajeerror = new \Magyp\MensajeBundle\Controller\MensajeError($request, $errores);
	    $mensajeerror->NoExpira()->Generar();
	    
	}
	    

	
	//die('mori controller');
	return $this->forward('MagypRendicionDeCajaBundle:Rendicion:detalleRendicion', array('idrendicion' => $id));
	//return $this->redirect($this->generateUrl('sistema_rendicion_detalle', array('idrendicion' => $id)));
        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Rendicion entity.
     *
     * @Route("/{id}/delete", name="sistema_rendicion_delete")
     * @Secure(roles="ROLE_RENDICION")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Rendicion entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('rendicion'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
	
    /**     
     * @Route("/datosdeprueba/{desde}/{hasta}", name="sistema_rendicion_datosdeprueba")
     * 
     */
    public function datosdepruebaAction($desde,$hasta)
    {	
		$rendiciones = array();
		$idarea= $this->getRequest()->get('idarea');
		$user= $this->getRequest()->get('user');
		$em = $this->getDoctrine()->getManager();
		
		if($user == '')$usuario = $this->getUsuario();		
		else $usuario = $em->getRepository("MagypRendicionDeCajaBundle:Usuario")->findOneByUsername($user);	
		if($idarea == '')$area = $usuario->getArea();
		//var_dump($usuario->getNombre());
		//die();
		
		for($i=$desde;$i<$hasta;$i++){
			$rendicion = new Rendicion();
			$rendicion->setFecha(new \DateTime());
			//$responsable = $em->getRepository("MagypRendicionDeCajaBundle:Usuario")->findOneByUsername('asd');
			$rendicion->setResponsable($usuario);
			$rendicion->setArea($area);
			//$subresponsable = $em->getRepository("MagypRendicionDeCajaBundle:Usuario")->findOneByUsername('fer1');
			//$rendicion->setSubresponsable($subresponsable);		
			$rendicion->setTotalRendicion(rand(100, 1000));
			$aux=str_pad(5000+$i,7, "0", STR_PAD_LEFT);
			$rendicion->setExpediente("S05:{$aux}/2012");
			$inicio = 40000 + rand(0,4000);
			
			$em->persist($rendicion);
			$comprobantes = ComprobanteController::CrearComprobantes($em, $inicio,+$inicio +rand(2,7),$rendicion);
			$rendiciones[] = $rendicion;
			
			
		}
		
		//var_dump($rendicion);
		for($i=$desde,$n=0;$i<$hasta;$i++,$n++){
				echo $rendiciones[$n]->getExpediente () . "<br>";
		
				$em->persist($rendiciones[$n]);
		}		
		//$em->persist($rendicion);
		$em->flush();
		$inicial= $rendiciones[0]->getExpediente();
		$final=$rendiciones[count($rendiciones)-1]->getExpediente();
		echo 'Creados de '.$inicial.' hasta '.$final;
		return new \Symfony\Component\HttpFoundation\Response();
	}
	
	
    /** Cuidado!! Borra todas las rendiciones de un usuario.
     * @Route("/borrar-rendiciones/usuario/{usuario}", name="sistema_rendicion_borrar_rendiciones_usuario")
     * @Secure(roles="ROLE_RENDICION")
     * @Template("MagypRendicionDeCajaBundle:Comprobante:detalle.html.twig")
     */
    public function borrarRendicionAction($usuario)
    {		
		$em = $this->getDoctrine()->getManager();
		$usuario = $em->getRepository("MagypRendicionDeCajaBundle:Usuario")->findOneByUsername($usuario);	
		$rendiciones = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->findByResponsable($usuario);
		for($i=0;$i<count($rendiciones);$i++){		    
		    $comprobantes = $rendiciones[$i]->getComprobantes();
		    for($j=0;$j<count($comprobantes);$j++){
			$em->remove($comprobantes[$j]);
		    }
		    $em->remove($rendiciones[$i]);
		}
		$em->flush();
		return new \Symfony\Component\HttpFoundation\Response();
    }	
    /**
     * Lists all Rendicion entities.
     *
     * @Secure(roles="ROLE_RENDICION")
     * @Route("/detalle/{idrendicion}", name="sistema_rendicion_detalle")
     * @Template("MagypRendicionDeCajaBundle:Comprobante:detalle.html.twig")
     */
    public function detalleRendicionAction($idrendicion)
    {
	$elusuarioesAF = $this->container->get('security.context')->isGranted('ROLE_AF');
	return self::detalleRendicionFuncion($this,$idrendicion, $elusuarioesAF );
    }
    
    public static function detalleRendicionFuncion($controller,$idrendicion,$elusuarioesAF = false)
    {
        $em = $controller->getDoctrine()->getManager();

        $rendicion = new Rendicion();
        $rendicion = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->find($idrendicion);
	if(is_null($rendicion)){
		echo 'No se encontro la rendicion elegida.';
		return new \Symfony\Component\HttpFoundation\Response();
	}	
        $comprobantes = $em->getRepository('MagypRendicionDeCajaBundle:Comprobante')->buscarActivos($idrendicion);
        $usuario = $controller->getUsuario();
        if ( $rendicion->getArea() !== $usuario->getArea() && $elusuarioesAF == false){	    
            return new \Symfony\Component\HttpFoundation\Response('NO puede acceder a rendiciones que no pertenecen a su area.');
            //return $controller->redirect($controller->generateUrl('sistema_rendicion'));
        }
	$total=0;
	$totaltipo2=0;
	$totaltipo3=0;
	$totaltipo4=0;
	foreach ($comprobantes as $comprobante ){
		$total+=$comprobante->getImporte();
	//	var_dump($comprobante->getImputacion()->getTipo());
		switch ($comprobante->getImputacion()->getTipo()->getId()) {
			case 2:
				$totaltipo2+=$comprobante->getImporte();
				break;
			case 3:
				$totaltipo3+=$comprobante->getImporte();
				break;
			case 4:
				$totaltipo4+=$comprobante->getImporte();
				break;
			default:
				break;
		}
	}
        $editForm = $controller->createForm(new RendicionType(), $rendicion);
        $editForm->remove('totalRendicion'); // el total no se modifica.
        
        
        $eRendicion = $em->getRepository("MagypRendicionDeCajaBundle:Rendicion")->find($idrendicion);

        $hashParaEnviar= md5($eRendicion->getId().$eRendicion->getResponsable().$eRendicion->getArea());
        
        $eArchivar = $em->getRepository("MagypRendicionDeCajaBundle:Archivar")->findOneBy(array( 'rendicion' => $eRendicion));
        return array(
			'comprobantes' => $comprobantes, 'total' => $total, 'idrendicion' => $idrendicion, 
			'totalTipo2' => $totaltipo2,
			'totalTipo3' => $totaltipo3,
			'totalTipo4' => $totaltipo4,
			'rendicion'      => $rendicion,
			'edit_form'   => $editForm->createView(),
                        'archivar' => $eArchivar,
            'hash' => $hashParaEnviar,            
        );
    }
	
    /**
     * @Route("/actualizar/{idrendicion}", name="sistema_rendicion_actualizar")
     * @Secure(roles="ROLE_RENDICION")
     * @Template()
     */
    public function actualizarAction($idrendicion)
    {
        $em = $this->getDoctrine()->getManager();
		$rendicion = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->find($idrendicion);
		$rendicion->Actualizar();
		$em->persist($rendicion);
		$em->flush();
        return new \Symfony\Component\HttpFoundation\Response();
    }	
	/**
     * @Route("/eventos/{idrendicion}", name="sistema_rendicion_eventos")
     * @Secure(roles="ROLE_LOG")
     * @Template()
     */
    public function eventosAction($idrendicion)
    {
		
		$eventos = $this->getCofra()->getEventoRendicion($idrendicion);
		$em = $this->getDoctrine()->getManager();
		\Magyp\RendicionDeCajaBundle\Entity\LogRegistroEntidad::setEntityManager($em);
		//$em->get
		return array('eventos' => $eventos);
		/*
        $em = $this->getDoctrine()->getManager();
        $eventoComprobante = $em->getRepository('MagypRendicionDeCajaBundle:EventoComprobante')->find($idrendicion);
        $eventos = $em->getRepository('MagypRendicionDeCajaBundle:EventoComprobante')->find($idrendicion);
		//$eventoRendicion= $em->getRepository('MagypRendicionDeCajaBundle:EventoRendicion')->find($id);
        $modifiaciones = $eventoComprobante->getListaDeCambios();
        echo "Se Modifico:<BR><BR>";
        foreach($modifiaciones as $item)echo $item->getCampo()."<BR>" ;
        var_dump($eventoComprobante->getListaDeCambios());
        return new \Symfony\Component\HttpFoundation\Response();
	 * *
	 */
    }	
	
	/**
     * @Secure(roles="ROLE_RENDICION")
     * @Route("/papelera", name="sistema_rendicion_papelera")
     * @Template("MagypRendicionDeCajaBundle:Rendicion:papelera.html.twig")
     */
    public function papeleraAction()
    {

		$em = $this->getDoctrine()->getManager();
		$area = $this->getUsuario()->getArea();
		//$rendiciones = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->BuscarPorArea($area);
		$rendiciones = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->BuscarBorradasPorArea($area);
		//var_dump($rendiciones);
		//var_dump($area->getId() );
		//$rendiciones = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->BuscarPorAreaBorrados($area);
        return array(
			'rendiciones'      => $rendiciones,
        );
	}
	
    /**
     * @Route("/borrar/{idrendicion}", name="sistema_rendicion_borrar")
     * @Secure(roles="ROLE_RENDICION")
     * @Template()
     */
    public function borrarAction($idrendicion)
    {
        $em = $this->getDoctrine()->getManager();
        $rendicion = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->find($idrendicion);
        if ( $rendicion->getEstado() == Estado::NUEVO ){
            $comprobantes = $rendicion->getComprobantes();
            foreach( $comprobantes  as $comprobante){
                $comprobante->setBorrado(1);
                $userCompleto = $this->getDoctrine()->getManager()->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($this->getUsuario()->getId());
                $em->refresh($userCompleto);
                $eventoComprobante = new EventoComprobante($userCompleto, EventoComprobante::BORRAR, $comprobante);
                $em->persist($eventoComprobante);
            }
            $rendicion->setBorrado(1);
			$this->getCofra()->addEventoRendicion(EventoRendicion::BORRAR, $rendicion);
//            $eventoRendicion = new EventoRendicion($this->getUsuario(), EventoRendicion::BORRAR, $rendicion);		
            $em->persist($rendicion);
            
            $em->flush();
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
	    $mensaje->setMensaje('Accion','Se borro la rendicion correctamente.')		    
                ->Exito()
                ->Generar();
            return $this->redirect($this->generateUrl('sistema_rendicion'));
            return new \Symfony\Component\HttpFoundation\Response();
        }else{
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
	    $mensaje->setMensaje('Error','No se puede borrar una rendicion en circulacion.')		    
	    ->Error()
	    ->Generar();
            return $this->redirect($this->generateUrl('sistema_rendicion'));
        }
    }		

	/**
     * @Route("/restaurar/{idrendicion}", name="sistema_rendicion_restaurar")
     * @Secure(roles="ROLE_RENDICION")
     * @Template()
     */
    public function restaurarAction($idrendicion)
    {
        $em = $this->getDoctrine()->getManager();
		$rendicion = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->find($idrendicion);
//              Se anula por que al restaurar, restaura todos los comprobantes. Ademas los comprobantes saltan las validaciones. 
//              Se deben restaurar cada uno.
//		$comprobantes = $rendicion->getComprobantes();
//		foreach( $comprobantes  as $comprobante){
//			$comprobante->setBorrado(0);
//			
//			$eventoComprobante = new EventoComprobante($this->getUsuario(), EventoComprobante::RESTAURAR, $comprobante );
//			$em->persist($eventoComprobante);
//		}
		$rendicion->setBorrado(0);
//		$eventoRendicion = new EventoRendicion($this->getUsuario(), EventoRendicion::RESTAURAR, $rendicion);		
//		$em->persist($eventoRendicion);
		$this->getCofra()->addEventoRendicion(EventoRendicion::RESTAURAR, $rendicion);
		$em->persist($rendicion);
		$em->flush();    
                
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                $mensaje->setMensaje('Accion','Se restauro la rendicion correctamente.')		    
                    ->Exito()
                    ->Generar();
                
		return $this->redirect($this->generateUrl('sistema_rendicion'));
    }		

    /**
     * @Secure(roles="ROLE_RENDICION")
     * @Route("/imprimir/{idrendicion}/{tipo}/{hash}", name="sistema_rendicion_imprimir")
     * @Template()
     */
    public function imprimirTipoAction($idrendicion, $tipo, $hash)
    {

        $em = $this->getDoctrine()->getManager();
        $rendicion = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->find($idrendicion);
        if (false === $this->container->get('security.context')->isGranted('ROLE_AF')) {
            //solo pueden imprimir los q sean del area de la rendicion o ( tiene permisos  ROLE_AF )

            if(is_null($rendicion)){
                $mensajeAEnviar= "No se encontro la rendicion elegida.";
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                $mensaje->setMensaje('Error',$mensajeAEnviar)		    
                ->Error()->NoExpira()
                ->Generar();
                return $this->redirect($this->generateUrl('sistema_rendicion'));
            }

            if ( $rendicion->getArea() !== $this->getUsuario()->getArea() ){
                $mensajeAEnviar= "Un usuario de otra area quiere ver la rendicion de otra area";
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                $mensaje->setMensaje('Error',$mensajeAEnviar)		    
                ->Error()->NoExpira()
                ->Generar();
                return $this->redirect($this->generateUrl('sistema_rendicion'));
            }
            
        }
        
        switch($tipo){
            case 2:
                $comprobantesAlmuerzos = $em->getRepository('MagypRendicionDeCajaBundle:Comprobante')->buscarActivosAlmuerzos($idrendicion);
                if (!$comprobantesAlmuerzos){
                    $mensajeAEnviar= "Esta rendicion no tiene comprobantes del timpo almuerzo para imprimir.";
                    $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                    $mensaje->setMensaje('Error',$mensajeAEnviar)		    
                    ->Error()->NoExpira()
                    ->Generar();
                    return $this->redirect($this->generateUrl('sistema_rendicion_detalle', array('idrendicion' => $idrendicion)));
                }
            case 1:
                
                $comprobantesTotales = $em->getRepository('MagypRendicionDeCajaBundle:Comprobante')->buscarActivosOrdenadosPorImputacion($idrendicion);
                if (!$comprobantesTotales){
                    $mensajeAEnviar= "Esta rendicion no tiene comprobantes para imprimir.";
                    $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                    $mensaje->setMensaje('Error',$mensajeAEnviar)		    
                    ->Error()->NoExpira()
                    ->Generar();
                    return $this->redirect($this->generateUrl('sistema_rendicion_detalle', array('idrendicion' => $idrendicion)));
                }
                return $this->redirect($this->generateUrl('imprimir_general_pdf', array("idrendicion" => $idrendicion, "tipo" => $tipo, "hash" => $hash), true) );
            break;
            default:
                die("tipo de impresion desconocida");
        }

    }   
    
    private function getErrorMessages(\Symfony\Component\Form\Form $form) {      
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


    
    /**
     *
     * @Route("/liquidacion/{idrendicion}", name="rendicion_imprimir_liquidacion")
     * @Secure(roles="ROLE_RENDICION")
     * @Template("MagypRendicionDeCajaBundle:Impresion:liquidacion.html.twig")
     */
    public function imprimirLiquidacion($idrendicion)
    {
        $em = $this->getDoctrine()->getManager();
        $rendicion = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->find($idrendicion);
        
        $aFechaMinMax= $em->getRepository('MagypRendicionDeCajaBundle:Comprobante')->fechaMinMaxdeRendicion($idrendicion);
        
        $total= $em->getRepository('MagypRendicionDeCajaBundle:Comprobante')->sumatoriaActivos($idrendicion);
        
        $actividad= str_pad($rendicion->getArea()->getActividad(), 2, "0", STR_PAD_LEFT);
        
        $iniciales= substr( $rendicion->getResponsable()->getNombre(), 0, 1).substr( $this->getUsuario()->getApellido(), 0, 1);
        
        $fuenteFinanciamiento= 11;
        
        return array(
            'rendicion' => $rendicion,
            'periodoinicial' => $aFechaMinMax[0][1],
            'periodofinal' => $aFechaMinMax[0][2],
            'total' => $total[0][1],
            'actividad' => $actividad,
            'fuentefinanciamiento' => $fuenteFinanciamiento,    
            'iniciales' => $iniciales
        );
        

    }

    	/**
     * @Route("/historial/{idrendicion}", name="sistema_rendicion_historial")
     * @Secure(roles="ROLE_AF")
     * @Template()
     */
    public function historialAction($idrendicion)
    {
        
		$eventos = $this->getCofra()->getEventoRendicion($idrendicion);
		$em = $this->getDoctrine()->getManager();
		\Magyp\RendicionDeCajaBundle\Entity\LogRegistroEntidad::setEntityManager($em);

		/*
		$eventos = $this->getCofra()->getEventoEstadoRendicion($idrendicion);
		$em = $this->getDoctrine()->getManager();
		\Magyp\RendicionDeCajaBundle\Entity\LogRegistroEntidad::setEntityManager($em);*/
		//$em->get
		return array('eventos' => $eventos);
		/*
        $em = $this->getDoctrine()->getManager();
        $eventoComprobante = $em->getRepository('MagypRendicionDeCajaBundle:EventoComprobante')->find($idrendicion);
        $eventos = $em->getRepository('MagypRendicionDeCajaBundle:EventoComprobante')->find($idrendicion);
		//$eventoRendicion= $em->getRepository('MagypRendicionDeCajaBundle:EventoRendicion')->find($id);
        $modifiaciones = $eventoComprobante->getListaDeCambios();
        echo "Se Modifico:<BR><BR>";
        foreach($modifiaciones as $item)echo $item->getCampo()."<BR>" ;
        var_dump($eventoComprobante->getListaDeCambios());
        return new \Symfony\Component\HttpFoundation\Response();
	 * *
	 */
    }	
	
    
}
				
