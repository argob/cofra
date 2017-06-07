<?php

namespace Magyp\RendicionDeCajaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\Comprobante;
use Magyp\RendicionDeCajaBundle\Form\ComprobanteType;
use Magyp\RendicionDeCajaBundle\Entity\EventoComprobante;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Comprobante controller.
 *
 * @Route("/sistema/comprobante")
 */
class ComprobanteController extends BaseCofraController {

    /**
     * Lists all Comprobante entities.
     *
     * @Route("/", name="sistema_comprobante")
     * @Secure(roles="ROLE_RENDICION")
     * @Template()
     */
    public function indexAction() {
	$em = $this->getDoctrine()->getManager();

	$entities = $em->getRepository('MagypRendicionDeCajaBundle:Comprobante')->findAll();

	return array(
	    'entities' => $entities,
	);
    }

    /**
     * Finds and displays a Comprobante entity.
     *
     * @Route("/{id}/show", name="sistema_comprobante_show")
     * @Secure(roles="ROLE_RENDICION")
     * @Template()
     */
    public function showAction($id) {
	$em = $this->getDoctrine()->getManager();

	$comprobante  = $em->getRepository('MagypRendicionDeCajaBundle:Comprobante')->find($id);

	if (!$comprobante ) {
	    throw $this->createNotFoundException('Unable to find Comprobante entity.');
	}

	$deleteForm = $this->createDeleteForm($id);

	return array(
	    'entity' => $comprobante ,
	    'delete_form' => $deleteForm->createView(),
	);
    }

    /**
     * Displays a form to create a new Comprobante entity.
     *
     * @Secure(roles="ROLE_RENDICION")
     * @Route("/nuevo/rendicion/{idrendicion}", name="sistema_comprobante_nuevo")
     * @Template()
     */
    public function nuevoAction($idrendicion) {
	$comprobante = new Comprobante();
	$em = $this->getDoctrine()->getManager();
	$rendicion = $em->getRepository("MagypRendicionDeCajaBundle:Rendicion")->find($idrendicion);
	$comprobante->setRendicion($rendicion);
	$form = $this->createForm(new ComprobanteType(), $comprobante);


	return array(
	    'comprobante' => $comprobante,
	    'form' => $form->createView(),
	    'idrendicion' => $idrendicion
	);
    }

    /**
     * Creates a new Comprobante entity.
     *
     * @Route("/create", name="sistema_comprobante_crear")
     * @Secure(roles="ROLE_RENDICION")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:Comprobante:nuevo.html.twig")
     */
    public function crearAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
		$em->clear();
	$comprobante = new Comprobante();
        $dataComprobante= $request->request->get('magyp_rendiciondecajabundle_comprobantetype');
        $idImputacion= $dataComprobante['imputacion'];
        $eImputacion= $em->getRepository('MagypRendicionDeCajaBundle:Imputacion')->find($idImputacion);
        $comprobante->setImputacion($eImputacion);
	$form = $this->createForm(new ComprobanteType(), $comprobante);
	$form->bind($request);
	if(is_numeric($comprobante->getRendicion()))$idrendicion = $comprobante->getRendicion();
	
	if ($form->isValid()) {
	    if($this->validoRendicionDisintaConMismoNumeroProveedor($em,$comprobante, $request, 0)){
		if( ( $this->validoLimite3000($em, $comprobante, $request, 0)) && ( $this->validoLimiteMontoArea( $em, $comprobante, $request, 0) ) && ( $this->validoRepiteNumeroProveedor( $em, $comprobante, $request, 0 ) ) ){            		    
		    $rendicion = $em->getRepository("MagypRendicionDeCajaBundle:Rendicion")->find($comprobante->getRendicion());
		    $comprobante->setRendicion($rendicion);
		    $rendicion->Actualizar();
		    $em->persist($comprobante);
		    $em->persist($rendicion);
            $userCompleto = $this->getDoctrine()->getManager()->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($this->getUsuario()->getId());
		    $eventoComprobante = new EventoComprobante($userCompleto, EventoComprobante::NUEVO, $comprobante, null, $this->getRequest());
		    $em->persist($eventoComprobante);
		    $em->flush();
		    return $this->redirect($this->generateUrl('sistema_rendicion_detalle', array('idrendicion' => $rendicion->getId())));
		}
	    }
	    return $this->redirect($this->generateUrl('sistema_comprobante_nuevo', array('idrendicion' => $idrendicion)));
        }else{
	    $errores = self::getErrorMessages($form);
	    if(count($errores)>0){
	    $mensajeerror = new \Magyp\MensajeBundle\Controller\MensajeError($request, $errores);
	    $mensajeerror->NoExpira()->Generar();
	    }
	}   

	
	
	
	return array(
	    'comprobante' => $comprobante,
	    'form' => $form->createView(),
	    'idrendicion' => $idrendicion
	);
    }

    /**
     * Displays a form to edit an existing Comprobante entity.
     * @Secure(roles="ROLE_RENDICION")
     * @Route("/{id}/edit", name="sistema_comprobante_edit")
     * @Template()
     */
    public function editAction($id) {
	$em = $this->getDoctrine()->getManager();

	$comprobante = $em->getRepository('MagypRendicionDeCajaBundle:Comprobante')->find($id);

	if (!$comprobante ) {
	    throw $this->createNotFoundException('Unable to find Comprobante entity.');
	}
        
        $esTicket= false;
        if ( $comprobante->getTipofactura()->getTipo() === "Ticket"){
            $esTicket= true;
        } 
        
	$editForm = $this->createForm(new ComprobanteType(), $comprobante );
	$deleteForm = $this->createDeleteForm($id);
       

	return array(
	    'entity' => $comprobante ,
            'esticketcontroller' => $esTicket,
	    'edit_form' => $editForm->createView(),
	    'delete_form' => $deleteForm->createView(),
	    'rendicion' => $comprobante->getRendicion()
	);
    }

    /**
     * Edits an existing Comprobante entity.
     *
     * @Route("/{id}/update", name="sistema_comprobante_update")
     * @Secure(roles="ROLE_RENDICION")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:Comprobante:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {

	$em = $this->getDoctrine()->getManager();
	$em->clear();
	$comprobante = $em->getRepository('MagypRendicionDeCajaBundle:Comprobante')->find($id);
	$rendicion = $comprobante->getRendicion(); // debe estar, si falla, cuando obtengo hago getrendicion, obtengo solo num
	$comprobanteAnterior = clone $comprobante;
	//$comprobanteAnterior = $comprobante;
	if (!$comprobante) {
	    throw $this->createNotFoundException('Unable to find Comprobante entity.');
	}
        
        $esTicket= false;
        if ( $comprobante->getTipofactura()->getTipo() === "Ticket"){
            $esTicket= true;
        } 
        
	$deleteForm = $this->createDeleteForm($id);
        
        $dataComprobante= $request->request->get('magyp_rendiciondecajabundle_comprobantetype');
        $idImputacion= $dataComprobante['imputacion'];
        $eImputacion= $em->getRepository('MagypRendicionDeCajaBundle:Imputacion')->find($idImputacion);
        $comprobante->setImputacion($eImputacion);
        
	$editForm = $this->createForm(new ComprobanteType(), $comprobante);
	$editForm->bind($request);

	if ($editForm->isValid()) {
	    if($this->validoRendicionDisintaConMismoNumeroProveedor($em,$comprobante, $request, 1)){
		if( ( $this->validoLimite3000($em, $comprobante, $request, 1)) && ( $this->validoLimiteMontoArea( $em, $comprobante, $request, 1) )  && ( $this->validoRepiteNumeroProveedor( $em, $comprobante, $request, 1 ) ) ){            		    
		    // transforma el id en entidad para la relacion
		    $rendicion = new \Magyp\RendicionDeCajaBundle\Entity\Rendicion;
		    $rendicion = $em->getRepository("MagypRendicionDeCajaBundle:Rendicion")->find($comprobante->getRendicion());

		    $comprobante->setRendicion($rendicion);
		    $rendicion->Actualizar();

		    $em->persist($comprobante);
		    $em->persist($rendicion);
            $userCompleto = $this->getDoctrine()->getManager()->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($this->getUsuario()->getId());
		    $eventoComprobante = new EventoComprobante($userCompleto, EventoComprobante::MODIFICIACION, $comprobante, $comprobanteAnterior, $this->getRequest());
		    $em->persist($eventoComprobante);
		    $cambios = $eventoComprobante->getListaDeCambios();
		    $em->flush();

		    return $this->redirect($this->generateUrl('sistema_rendicion_detalle', array('idrendicion' => $rendicion->getId())));
		}
	    }
            return array(
            'id' => $id,
	    'entity' => $comprobante,
            'esticketcontroller' => $esTicket,
	    'edit_form' => $editForm->createView(),
	    'rendicion' => $rendicion
	);

	}else{
	    $errores = self::getErrorMessages($editForm);
	    if(count($errores)>0){
	    $mensajeerror = new \Magyp\MensajeBundle\Controller\MensajeError($request, $errores);
	    $mensajeerror->NoExpira()->Generar();
	    }
	}   

	return array(
	    'entity' => $comprobante,
            'esticketcontroller' => $esTicket,
	    'edit_form' => $editForm->createView(),
	    'rendicion' => $rendicion
	);
    }

    /**
     * Deletes a Comprobante entity.
     *
     * @Route("/{id}/delete", name="sistema_comprobante_delete")
     * @Secure(roles="ROLE_RENDICION")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id) {
	$form = $this->createDeleteForm($id);
	$form->bind($request);

	if ($form->isValid()) {
	    $em = $this->getDoctrine()->getManager();
	    $comprobante  = $em->getRepository('MagypRendicionDeCajaBundle:Comprobante')->find($id);

	    if (!$comprobante ) {
		throw $this->createNotFoundException('Unable to find Comprobante entity.');
	    }

	    $em->remove($comprobante );
	    $em->flush();
	}

	return $this->redirect($this->generateUrl('comprobante'));
    }

    private function createDeleteForm($id) {
	return $this->createFormBuilder(array('id' => $id))
			->add('id', 'hidden')
			->getForm()
	;
    }

    /**
     * @Route("/datosdeprueba/{desde}/{hasta}", name="sistema_comprobante_datosdeprueba")
     * 
     */
    public function datosdepruebaAction($desde, $hasta) {
	$em = $this->getDoctrine()->getManager();
	self::CrearComprobantes($em,$desde, $hasta);
	return new \Symfony\Component\HttpFoundation\Response();
    }
    
    public static function CrearComprobantes($em, $desde, $hasta, $rendicion = null) {
	
	$imputaciones = $em->getRepository("MagypRendicionDeCajaBundle:Imputacion")->findAll();
	$proveedores = $em->getRepository("MagypRendicionDeCajaBundle:Proveedor")->findAll();
	$rendiciones = $em->getRepository("MagypRendicionDeCajaBundle:Rendicion")->findAll();
	$comprobantes = array();

	for ($i = $desde; $i < $hasta; $i++) {
	    echo $i;
	    $comprobante = new Comprobante();	    
	    $aux = str_pad($i, 5, "0", STR_PAD_LEFT);
	    $comprobante->setDescripcion("Descripcion {$aux}");

	    $comprobante->setImporte(rand(100, 1000));
	    $comprobante->setNumero($i);
	    //$comprobante->setNumeroFoja(500 + $i);
	    $comprobante->setFecha(new \DateTime());
	    

	    $comprobante->setImputacion($imputaciones[rand(0, count($imputaciones) - 1)]);
	    $comprobante->setProveedor($proveedores[rand(0, count($proveedores) - 1)]);
	    if(is_null($rendicion))$comprobante->setRendicion($rendiciones[rand(0, count($rendiciones) - 1)]);
	    else $comprobante->setRendicion($rendicion);
	    $comprobantes[] = $comprobante;
	    $em->persist($comprobante);
	}

	$em->flush();
	return $comprobantes;
	
    }

    /**
     * Lista comprobantes de rendicion especifica - Se usa con Ajax
     *
     * @Route("/rendicion/{idrendicion}", name="sistema_comprobante_lista")
     * @Secure(roles="ROLE_RENDICION")
     * @Template()
     */
    public function listaAction($idrendicion) {
	$em = $this->getDoctrine()->getManager();

	//$comprobantes = $em->getRepository('MagypRendicionDeCajaBundle:Comprobante')->findByRendicion($rendicion);
	$comprobantes = $em->getRepository('MagypRendicionDeCajaBundle:Comprobante')->buscarActivos($idrendicion);
	return array(
	    'comprobantes' => $comprobantes,
	);
    }

    /**
     * @Route("/crear/listo", name="sistema_comprobante_crear_listo")
     * @Template()
     */
    public function mensajeAction() {
	return array();
    }

    /**
     * @Route("/evento/{id}", name="sistema_comprobante_evento")
     * @Secure(roles="ROLE_LOG")
     * @Template()	
     */
    public function eventoAction($id) {
	$em = $this->getDoctrine()->getManager();
	$eventoComprobante = $em->getRepository('MagypRendicionDeCajaBundle:EventoComprobante')->find($id);
	$modifiaciones = $eventoComprobante->getListaDeCambios();
	echo "Se Modifico:<BR><BR>";
	foreach ($modifiaciones as $item)
	echo $item->getCampo() . "<BR>";
	var_dump($eventoComprobante->getListaDeCambios());
	return new \Symfony\Component\HttpFoundation\Response();
    }

    /**
     * @Route("/{id}/eventos", name="sistema_comprobante_eventos")
     * @Secure(roles="ROLE_LOG")
     * @Template()	
     */
    public function eventosAction($id) {
	$em = $this->getDoctrine()->getManager();
	//$eventos = $em->getRepository('MagypRendicionDeCajaBundle:EventoComprobante')->findByComprobante($id);
	//$modifiaciones = $eventoComprobante->getListaDeCambios();
	//var_dump($eventos);
	$eventos = $this->getCofra()->getEventoComprobante($id);
	\Magyp\RendicionDeCajaBundle\Entity\LogRegistroEntidad::setEntityManager($em);
	
	return array('eventos' => $eventos);
    }

    /**
     * @Route("/{idcomprobante}/borrar", name="sistema_comprobante_borrar")
     * @Secure(roles="ROLE_RENDICION")
     * @Template()	
     */
    public function borrarAction($idcomprobante) {
	$em = $this->getDoctrine()->getManager();
	$comprobante = $em->getRepository('MagypRendicionDeCajaBundle:Comprobante')->find($idcomprobante);
	$comprobante->setBorrado(1);
	$em->persist($comprobante);
    $userCompleto = $this->getDoctrine()->getManager()->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($this->getUsuario()->getId());
	$em->refresh($userCompleto);
    $eventoComprobante = new EventoComprobante($userCompleto, EventoComprobante::BORRAR, $comprobante, null, $this->getRequest());
	$em->persist($eventoComprobante);
	$em->flush();
	$idrendicion = $comprobante->getRendicion()->getId();

	return $this->redirect($this->generateUrl('sistema_rendicion_detalle', array('idrendicion' => $idrendicion)));
    }

    /**
     * @Route("/{idcomprobante}/restaurar", name="sistema_comprobante_restaurar")
     * @Secure(roles="ROLE_RENDICION")
     * @Template()	
     */
    public function restaurarAction($idcomprobante) {
	$em = $this->getDoctrine()->getManager();
	$comprobante = $em->getRepository('MagypRendicionDeCajaBundle:Comprobante')->find($idcomprobante);
        $request= $this->getRequest();
        if($this->validoRendicionDisintaConMismoNumeroProveedor($em,$comprobante, $request, 0)){
		if( ( $this->validoLimite3000($em, $comprobante, $request, 1)) && ( $this->validoLimiteMontoArea( $em, $comprobante, $request, 1) )  && ( $this->validoRepiteNumeroProveedor( $em, $comprobante, $request, 1 ) ) ){             		    
                $comprobante->setBorrado(0);
                $em->persist($comprobante);
                $userCompleto = $this->getDoctrine()->getManager()->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($this->getUsuario()->getId());
                $em->refresh($userCompleto);
                $eventoComprobante = new EventoComprobante($userCompleto, EventoComprobante::RESTAURAR, $comprobante, null, $this->getRequest());
                $em->persist($eventoComprobante);
                $em->flush();
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                $mensaje->setMensaje('Accion','Se restauro el comprobante correctamente.')		    
                    ->Exito()
                    ->Generar();
           }
        }
	
	$idrendicion = $comprobante->getRendicion()->getId();

	return $this->redirect($this->generateUrl('sistema_comprobante_papelera', array('idrendicion' => $idrendicion)));
    }

    /**
     *
     * @Route("/papelera/{idrendicion}", name="sistema_comprobante_papelera")
     * @Secure(roles="ROLE_RENDICION")
     * @Template("MagypRendicionDeCajaBundle:Comprobante:papelera.html.twig")
     */
    public function papeleraAction($idrendicion) {

	$em = $this->getDoctrine()->getManager();
	$rendicion = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->find($idrendicion);
	
	if (is_null($rendicion)) {
	    echo 'No se encontro la rendicion elegida.';
	    return new \Symfony\Component\HttpFoundation\Response();
	}
	$eventoComprobantes = $em->getRepository('MagypRendicionDeCajaBundle:EventoComprobante')->comprobantesBorrados($idrendicion);
	$comprobantes = $em->getRepository('MagypRendicionDeCajaBundle:Comprobante')->buscarBorradosOrdenados($idrendicion);
	foreach ($eventoComprobantes as $eventoComprobante) {
	    $comprobantesOrdenados[] = $eventoComprobante->getComprobante();
	}

	$usuario = $this->getUsuario();
	$elusuarioesAF = $this->container->get('security.context')->isGranted('ROLE_AF');
	if ($rendicion->getArea() !== $usuario->getArea() && $elusuarioesAF == false) {
	    
	    return $this->redirect($this->generateUrl('sistema_rendicion'));
	}

	$refer = $this->getRequest()->get('refer');
	$id = $this->getRequest()->get('id');
	if($refer == "rendicion_papelera"){ $ruta_volver = $this->generateUrl('sistema_rendicion_papelera');}
	else { $ruta_volver= null; }
	return array(
	    'comprobantes' => $comprobantes,
	    //'comprobantes' => $comprobantesOrdenados,
	    'eventocomprobantes' => $eventoComprobantes,
	    'idrendicion' => $idrendicion,
	    'rendicion' => $rendicion,
	    'ruta_volver' => $ruta_volver
	);
    }

    public function validoImputacionNumeroComprobante($em,$comprobante, $request){
	//devuelve false si esta repetida
//	echo '<pre>';
//	var_dump($comprobante);
//	echo '</pre>';
	$bvalidoImputacionNumeroComprobante = $em->getRepository('MagypRendicionDeCajaBundle:Comprobante')->esValidoImputacionYNumeroComprobante
                ($comprobante->getRendicion(), $comprobante->getNumero(), $comprobante->getImputacion()->getId(), $comprobante->getId() );
        
        if ( $bvalidoImputacionNumeroComprobante != false ) return true;
	else {
	    $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
	    $mensaje->setMensaje('Accion','El numero de comprobante con esa imputacion esta repetida en la rendicion.')		    
	    ->Error()
	    ->Generar();
	    return false;
	       //die ("El numero de coomprobante con esa imputacion esta repetida en la rendicion");
	}
         
	}

    public function validoLimite3000($em, $comprobante, $request, $caso){            
	$bvalidoLimite3000ProveedorRendicion= $em->getRepository('MagypRendicionDeCajaBundle:Comprobante')->esValidoLimite3000ProveedorRendicion
		( $comprobante->getRendicion(), $comprobante->getProveedor()->getId(), $comprobante->getNumero(), $comprobante->getImporte(), $comprobante->getId(), $caso );

	if ( $bvalidoLimite3000ProveedorRendicion != false ) return true;
	else{
	    $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
	    $mensaje->setMensaje('Accion','La sumatoria de comprobantes relacionado al proveedor supera los 3000 pesos')		    
	    ->Error()
	    ->Generar();
	    return false;
	    //die ("La sumatoria de comprobantes relacionado al proveedor supera los 3000 pesos");
	}

    }
    
    
    public static function getErrorMessages(\Symfony\Component\Form\Form $form) {      
    $errors = array();

    //if ($form->hasChildren()) {
        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = self::getErrorMessages($child);
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
     * Displays a form to create a new Comprobante entity.
     *
     * @Secure(roles="ROLE_RENDICION")
     * @Route("/nuevo2/rendicion/{idrendicion}", name="sistema_comprobante_nuevo2")
     * @Template()
     */
    public function nuevo2Action($idrendicion) {
	$comprobante = new Comprobante();
	$em = $this->getDoctrine()->getManager();
	$rendicion = $em->getRepository("MagypRendicionDeCajaBundle:Rendicion")->find($idrendicion);
	$comprobante->setRendicion($rendicion);
	$form = $this->createForm(new ComprobanteType(), $comprobante);


	return array(
	    'comprobante' => $comprobante,
	    'form' => $form->createView(),
	    'idrendicion' => $idrendicion
	);
    }
    
    public function validoLimiteMontoArea( $em, $comprobante, $request, $caso){
        $eRendicion= $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->find($comprobante->getRendicion());
        $bValidoLimiteMontoArea= $em->getRepository('MagypRendicionDeCajaBundle:Comprobante')->esValidoLimiteMontoArea
		( $eRendicion->getId(), $eRendicion->getArea()->getMonto(), $comprobante->getImporte(), $comprobante->getId(), $caso );

	if ( $bValidoLimiteMontoArea != false ) return true;
	else{
	    $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
	    $mensaje->setMensaje('Accion','La sumatoria de comprobantes en esta rendicion supera los '.$eRendicion->getArea()->getMonto().', el mismo es el limite de gastos por rendicion de su area')		    
	    ->Error()
	    ->Generar();
	    
	    //die ("La sumatoria de comprobantes en esta rendicion supera el limite de gasto por rendicion de su area-".$comprobante->getRendicion()->getArea()->getMonto());
            return false;
	}
    }
    
    public function validoRepiteNumeroProveedor( $em, $comprobante, $request, $caso){
        $bValidoNoRepiteNumeroProveedor= $em->getRepository('MagypRendicionDeCajaBundle:Comprobante')->noRepiteNumeroProveedor
		( $comprobante->getNumero(), $comprobante->getProveedor()->getId(), $comprobante->getId(), $comprobante->getImputacion()->getId(), $caso );
        
        if ($comprobante->getImputacion()->getCodigo() == "3.7.1" ){
            return true;
        }
	if ( $bValidoNoRepiteNumeroProveedor != false ) return true;
	else{
	    $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
	    $mensaje->setMensaje('Accion','El numero de factura '.$comprobante->getNumero().' con el proveedor '.$comprobante->getProveedor()->getDescripcion().' e imputacion '.$comprobante->getImputacion()->getCodigo().', ya se encuentra cargado en alguna rendicion.')		    
	    ->Error()
	    ->Generar();
	    
	    //die ("La sumatoria de comprobantes en esta rendicion supera el limite de gasto por rendicion de su area-".$comprobante->getRendicion()->getArea()->getMonto());
            return false;
	}
    }
    
    public function validoRendicionDisintaConMismoNumeroProveedor($em, $comprobante, $request, $caso){
        $bValidoNoRepiteNumeroProveedor= $em->getRepository('MagypRendicionDeCajaBundle:Comprobante')->rendicionDisintaConMismoNumeroProveedor
		( $comprobante->getRendicion(), $comprobante->getNumero(), $comprobante->getProveedor()->getId(), $comprobante, $caso );
	
        if ($comprobante->getImputacion()->getCodigo() == "3.7.1" ){
            return true;
        }
        
        if ( ( $bValidoNoRepiteNumeroProveedor != false ) )return true;
	else{
	    $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
	    $mensaje->setMensaje('Accion','El numero de factura '.$comprobante->getNumero().' con el proveedor '.$comprobante->getProveedor()->getDescripcion().' ya se encuentra cargado en otra rendicion.')		    
	    ->Error()
	    ->Generar();
            return false;
	}
        
    }
}
