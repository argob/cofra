<?php

namespace Magyp\RendicionDeCajaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Form\NotificacionType;
use Magyp\RendicionDeCajaBundle\Entity\Notificacion;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Magyp\RendicionDeCajaBundle\Form\MotivoType;
use Magyp\RendicionDeCajaBundle\Entity\Estado;
use Magyp\RendicionDeCajaBundle\Entity\EventoNotificacion;

/**
 * Notificacion controller.
 *
 * @Route("/sistema/notificacion")
 */
class NotificacionController extends BaseCofraController
{
    /**
     * @Route("/", name="sistema_notificacion_index")
     * @Template()
     */
    public function indexAction()
    {
	return array();
    }
    /**
     * 
     * @Secure(roles="ROLE_ADMINISTRADOR")
     * @Route("/ver", name="sistema_notificacion_ver")
     * @Route("/ver/area/{idarea}", name="sistema_notificacion_ver_area")
     * @Template()
     */
    public function verAction($idarea = -1)
    {
	//$idarea= $this->getRequest()->get("area");
        $em = $this->getDoctrine()->getManager();
	$form = $this->createForm(new NotificacionType(), null);
	if ($idarea > 0){
	    //$notificaciones = $em->getRepository('MagypRendicionDeCajaBundle:Notificacion')->findByDestino($idarea);
	    $notificaciones = $em->getRepository('MagypRendicionDeCajaBundle:Notificacion')->buscarPorDestino($idarea,"DESC");
	    if(isset($notificaciones[0])) $area = $notificaciones[0]->getDestino();
	    else $area = '';
	}
	elseif ($idarea == -1){
	    $notificaciones = $em->getRepository('MagypRendicionDeCajaBundle:Notificacion')->misnotificaciones($this->getUsuario());
	    $area = "Mis Notificaciones";
	}elseif ($idarea == 0){
	    $notificaciones = $em->getRepository('MagypRendicionDeCajaBundle:Notificacion')->TodasPorOrdenDescendente();
	    $area = "Todas";
	}
	
        //$notificacion = $em->getRepository('MagypRendicionDeCajaBundle:Notificacion')->findAll();
        return array('notificaciones' => $notificaciones,
		     'form' => $form->createView(),
		     'area' => $area
            
        );
    }

    /**
     * Route("/nueva", name="sistema_notificacion_nueva")
	 * Secure(roles="ROLE_NOTIFICACION")
     * Template()
     */
    public function nuevaAction()
    {
        $em = $this->getDoctrine()->getManager();

	$notificacion = new \Magyp\RendicionDeCajaBundle\Entity\Notificacion();
	$form = $this->createForm(new NotificacionType(), $notificacion);


	return array(
	    'notificacion' => $notificacion,
	    'form' => $form->createView(),
	    
	);
        
    }
     /**
     * @Route("/crear", name="sistema_notificacion_crear")
	 * @Secure(roles="ROLE_NOTIFICACION")
     * @Template()
     */
    public function crearAction(Request $request)
    {
	$em = $this->getDoctrine()->getManager();
    $em->clear();
    $userCompleto = $this->getDoctrine()->getManager()->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($this->getUsuario()->getId());
    $em->refresh($userCompleto);
	$notificacion = new \Magyp\RendicionDeCajaBundle\Entity\Notificacion($userCompleto); // aca se le especifica el usuario-
	$form = $this->createForm(new NotificacionType(), $notificacion);
	$form->bind($request);
	
	if ($form->isValid()) {
	    $em->persist($notificacion);
	    $em->flush();
	    $this->getCofra()->crearMensajedeExito("Nueva notificacion", "Se ha enviado la notificacion");
		    //var_dump($notificacion);
	    return $this->redirect($this->generateUrl('sistema_notificacion_nueva'));
	}else{
	    $errores = ComprobanteController::getErrorMessages($form);
	    if(count($errores)>0){
	    $mensajeerror = new \Magyp\MensajeBundle\Controller\MensajeError($request, $errores);
	    $mensajeerror->NoExpira()->Generar();
	    return $this->render("MagypRendicionDeCajaBundle:Notificacion:nueva.html.twig", array('notificacion' => $notificacion,'form' => $form->createView() ));
	    }
	}
	
	return $this->redirect($this->generateUrl('sistema_notificacion_nueva'));
    }

    /**
     * @Route("/prueba")
     * 
     */
    public function pruebaAction(Request $request)
    {
	$em = $this->getDoctrine()->getManager();
	//$ec = $em->getRepository('MagypRendicionDeCajaBundle:Evento')->getEventoComprobantes();
	return new \Symfony\Component\HttpFoundation\Response();
    }
    
    
    public function mostrar($fila,$item){
	echo 'mostrada';
	return $fila->getId();
    }
    
     /**
     * @Route("/todas", name="sistema_notificacion_todas")
	 * @Secure(roles="ROLE_ADMINISTRADOR")
     * @Template()
     */
    public function todasAction(Request $request)
    {
	$em = $this->getDoctrine()->getManager();
        $texto = $this->getRequest()->get('notificacion_buscar');
        if(empty($texto))
            $qbnotificaciones = $em->getRepository('MagypRendicionDeCajaBundle:Notificacion')->qbTodas();
        else{
           // var_dump(strpos($texto,','));
            if(strpos($texto,',')!==false)
                $qbnotificaciones = $em->getRepository('MagypRendicionDeCajaBundle:Notificacion')->qbBuscarEnTodasPersonalizado($texto);    
            else        
                $qbnotificaciones = $em->getRepository('MagypRendicionDeCajaBundle:Notificacion')->qbBuscarEnTodas($texto);
        }
        $area = $this->getUsuario()->getArea();
        $notificaciones = $this->Paginar($qbnotificaciones);
        
	return array("notificaciones" => $notificaciones,"area" => $area, 'tipo'=>'entrantes');
	
    }
     /**
     * @Route("/propias", name="sistema_notificacion_propias")
	 * @Secure(roles="ROLE_NOTIFICACION")
     * @Route("/propias/{tipo}", name="sistema_notificacion_propias_tipo")
     * @Route("/propias/{tipo}/pagina/{pagina}", name="sistema_notificacion_propias_tipo_paginado")
     * @Template()
     */
    public function propiasAction($tipo = "entrantes", $pagina=1, $cantxpaginas= 20)
    {
	$em = $this->getDoctrine()->getManager();	
//	if($tipo == "entrantes"){
//	    $qbnotificaciones = $em->getRepository('MagypRendicionDeCajaBundle:Notificacion')->qbNotificacionesEntrantes($this->getUsuario());
//	}
//	if($tipo == "salientes"){
//	    $qbnotificaciones = $em->getRepository('MagypRendicionDeCajaBundle:Notificacion')->qbNotificacionesSalientes($this->getUsuario());
//	}
        $texto = $this->getRequest()->get('notificacion_buscar');

	if($tipo == "entrantes"){
	    $qbnotificaciones = $em->getRepository('MagypRendicionDeCajaBundle:Notificacion')->qbNotificacionesEntrantes($this->getUsuario(), $texto);
	}
	if($tipo == "salientes"){
	    $qbnotificaciones = $em->getRepository('MagypRendicionDeCajaBundle:Notificacion')->qbNotificacionesSalientes($this->getUsuario(), $texto);
	}

        $notificaciones = $this->Paginar($qbnotificaciones);
	$area = $this->getUsuario()->getArea();
        
        //$this->getCofra()->dump($notificaciones);
        
	return array('notificaciones' => $notificaciones,'area' => $area,'tipo' => $tipo);
	
    }

     /**
     * @Route("/{idnotificacion}/motivo", name="sistema_notificacion_motivo")
     * @Template()
     */
    public function motivoAction($idnotificacion) {
        
        $motivo = $em->getRepository('MagypRendicionDeCajaBundle:Motivo')->buscarPorNotificacion($idnotificacion);
        
        return array('motivo' => $motivo);
    }
     /**
     * @Route("/{idnotificacion}/rendicion/{idrendicion}/motivo/nuevo", name="sistema_notificacion_motivo_nuevo")
     * @Template()
     */
    public function nuevomotivoAction($idnotificacion,$idrendicion) {
        
        $em = $this->getDoctrine()->getManager();
        $notificacion = $em->getRepository('MagypRendicionDeCajaBundle:Notificacion')->find($idnotificacion);
	$motivo = is_null($notificacion->getMotivo()) ? null : $notificacion->getMotivo() ;
        
        //$this->getCofra()->dump($motivo);
	$form = $this->createForm(new MotivoType(), $motivo);


	return array(
	    'motivo' => $motivo,
	    'form' => $form->createView(),
            'idnotificacion' => $idnotificacion,
            'idrendicion' => $idrendicion
	    
	);        
        
     
    }
    /**
     * @Route("/{idnotificacion}/rendicion/{idrendicion}/motivo/crear", name="sistema_notificacion_motivo_crear")
     * @Template()
     */
    public function crearMotivoAction($idnotificacion,Request $request,$idrendicion)
    {
	$em = $this->getDoctrine()->getManager();
	$motivo = new \Magyp\RendicionDeCajaBundle\Entity\Motivo();
	$form = $this->createForm(new MotivoType(), $motivo);
	$form->bind($request);
	
        
        
	if ($form->isValid()) {
            $notificacion = $em->getRepository('MagypRendicionDeCajaBundle:Notificacion')->find($idnotificacion);
            //$rendicion = new \Magyp\RendicionDeCajaBundle\Entity\Rendicion();
            $rendicion = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->find($idrendicion);
            $rendicion->setIdultimanotificacion($idnotificacion);
            $motivo->setNotificacion($notificacion);
            $notificacion->setMotivo($motivo);
	    $em->persist($motivo);
	    $em->persist($rendicion);
	    $em->persist($notificacion);
	    $em->flush();
	    $this->getCofra()->crearMensajedeExito("Nueva notificacion", "Se ha enviado la notificacion");

	    return $this->redirect($this->generateUrl('af_rendiciones_home'));
	}else{
	    $this->getCofra()->crearMensajedeError("Falla notificacion", "Los datos no son correctos");
            return $this->redirect($this->generateUrl('sistema_notificacion_motivo_nuevo',array('idnotificacion' => $idnotificacion, 'idrendicion' => $idrendicion)));
	}
	
	
    }    
    /**
     * @Route("/{idnotificacion}/rendicion/{idrendicion}/motivo/ver", name="sistema_notificacion_motivo_ver")
     * @Template()
     */
    public function vermotivoAction($idnotificacion,$idrendicion)
    {    
        $em = $this->getDoctrine()->getManager();
        $notificacion = $em->getRepository('MagypRendicionDeCajaBundle:Notificacion')->find($idnotificacion);
        
        //$this->getCofra()->dump($notificacion);
        return array('notificacion' => $notificacion,
                'idrendicion' => $idrendicion);
    }
    
    public function Paginar($qb) {
        
        $paginador =  $this->get('knp_paginator');
		$cantidad = $this->get('request')->query->get('cant');
		$cantidad = intval($cantidad) > 0  ? intval($cantidad) : 20;

	return $paginador->paginate(
	    $qb->getQuery(),
	    $this->get('request')->query->get('page', 1),
	    $cantidad
	 );
        
    }
    
    /**
     * @Route("/marcarleido/{id}", name="sistema_notificacion_marcar_leido")
     */
    public function marcarLeido($id){
        $em = $this->getDoctrine()->getManager();
        $notificacion = $em->getRepository('MagypRendicionDeCajaBundle:Notificacion')->find($id);
        if ($notificacion->getLeido() == 0 ){
            $notificacion->setLeido(true);
            $em->persist($notificacion);
            $em->flush();
            $userCompleto = $this->getDoctrine()->getManager()->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($this->getUsuario()->getId());
            $eventoNotificacion = new EventoNotificacion($userCompleto, EventoNotificacion::NOTIFICACIONLEIDA, $notificacion,null,$this->getRequest());
            $em->persist($eventoNotificacion);
            $em->flush();
        }
        return new Response();
    }
    
    /**
     * @Route("/{id}/eventos", name="sistema_notificacion_eventos")
     * @Secure(roles="ROLE_LOG")
     * 
     * @Template()	
     */
    public function eventosAction($id)
    {
        $em = $this->getDoctrine()->getManager();
            $eventos = $em->getRepository('MagypRendicionDeCajaBundle:EventoNotificacion')->findByNotificacion($id);
        \Magyp\RendicionDeCajaBundle\Entity\LogRegistroEntidad::setEntityManager($em);
        return array('eventos' => $eventos);
    }

}
