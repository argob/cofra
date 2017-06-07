<?php

namespace Magyp\RendicionDeCajaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * 
 * @Route("/sistema/busqueda")
 */
class BusquedaController extends BaseCofraController
{
    /**
     *
     * @Route("/", name="sistema_busqueda")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        //$entities = $em->getRepository('MagypRendicionDeCajaBundle:Comprobante')->findAll();
		

        return array( );
    }

    /**
     *
     * @Route("/proveedor", name="sistema_busqueda_proveedor")
     * @Template()
     */
    public function proveedorAction()
    {
        return array();
    }

     /**
     * @Route("/proveedor/datos", name="sistema_busqueda_proveedor_lista")
     * @Template()
     */
    public function proveedorListaAction()
    {
	$em = $this->getDoctrine()->getManager();
	$proveedores = $em->getRepository('MagypRendicionDeCajaBundle:Proveedor')->buscarProveedores();
        return array('proveedores' => $proveedores );	
    }

    /**
     *
     * @Route("/content/xxxcountries.txt", name="sistema_busqueda_datosprueba")
     * @Template()
     */
    public function datospruebaAction()
    {
        return array();
    }
	
    /**
     *
     * @Route("/imputacion", name="sistema_busqueda_imputacion")
     * @Template()
     */
    public function imputacionAction()
    {
		$comprobante  = new \Magyp\RendicionDeCajaBundle\Entity\Comprobante();
        $form = $this->createForm(new \Magyp\RendicionDeCajaBundle\Form\ComprobanteType(), $comprobante);
        $form = $form->createView();
//		$a = $form->getChildren();
//		$b = $a['imputacion']->getVars();
//		var_dump($b['id']);
        return array('form' => $form);
    }

    /**
     *
     * 
	 * @Route("/imputacion/datos", name="sistema_busqueda_imputacion_lista")
     * @Template()
     */
    public function imputacionListaAction()
    {
	$em = $this->getDoctrine()->getManager();
	$imputaciones = $em->getRepository('MagypRendicionDeCajaBundle:Imputacion')->buscarImputaciones();
        return array('imputaciones' => $imputaciones);
    }

    /**
     *
     * @Route("/rendicion2", name="sistema_busqueda_rendicion2")
     * @Template()
     */
    public function rendicion2Action()
    {
	$comprobante  = new \Magyp\RendicionDeCajaBundle\Entity\Comprobante();
        $form = $this->createForm(new \Magyp\RendicionDeCajaBundle\Form\ComprobanteType(), $comprobante);
        $form = $form->createView();

        return array('form' => $form);
    }

    /**
     * @Route("/rendicion/datos", name="sistema_busqueda_rendicion_lista")
     * @Template()
     */
    public function rendicionListaAction()
    {
        $em = $this->getDoctrine()->getManager();
        $rendiciones = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->findAll();
        return array('rendiciones' => $rendiciones);
    }
    /**
    * @Route("/rendicion/buscada", name="sistema_busqueda_rendicion_buscada")
    * @Template()
    */
    public function rendicionBuscadaListaAction()
    {	
        $em = $this->getDoctrine()->getManager();
        $idrendicion = $this->getRequest()->get('rendicion_id');

       if (!empty($idrendicion) ){
            $eRendicion = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->find($idrendicion);
            if ($eRendicion){
                if($this->getUsuario()->getArea()->esAF()){
                    return $this->redirect($this->generateUrl('af_rendicion', array('idrendicion' => $idrendicion)));
                }else{
                    return $this->redirect($this->generateUrl('sistema_rendicion_detalle', array('idrendicion' => $idrendicion)));
                }
            }
        }
        $request= $this->getRequest();
        $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
        $mensaje->setMensaje('Error', "Rendicion inexistente")
                ->Error()
                ->Generar();
        return $this->redirect($this->generateUrl('sistema_busqueda_rendicion'));
    }
	
    /**
     * @Route("/usuarios", name="usuario_busqueda")
     * @Template("MagypRendicionDeCajaBundle:Usuario:index.html.twig")
     */
    public function busquedaAction()
    {
        
        $texto = $this->getRequest()->get('usuario_buscar');
        $em = $this->getDoctrine()->getManager();

        $qbusuarios = $em->getRepository('MagypRendicionDeCajaBundle:Usuario')->qb_Usuarios($texto);
        
	$paginador =  $this->get('knp_paginator');
	$cantidad = $this->get('request')->query->get('cantidad');
	$cantidad = intval($cantidad) > 0  ? intval($cantidad) : 15;

	$usuarios = $paginador->paginate(
	    $qbusuarios->getQuery(),
	    $this->get('request')->query->get('page', 1),
	    $cantidad
	 );        

        return array(
            'usuarios' => $usuarios,
        );
    }    
    /**
     * @Route("/areas", name="busqueda_area")
     * @Template("MagypRendicionDeCajaBundle:Area:index.html.twig")
     */
    public function AreaAction()
    {
        //$idusuario = $this->getRequest()->get('usuario_id');
        $texto = $this->getRequest()->get('area_buscar');
        $em = $this->getDoctrine()->getManager();
        $qbAreas = $em->getRepository('MagypRendicionDeCajaBundle:Area')->qb_areas($texto);

	$paginador =  $this->get('knp_paginator');
	$cantidad = $this->get('request')->query->get('cant');
	$cantidad = intval($cantidad) > 0  ? intval($cantidad) : 10;
	
        $areas = $paginador->paginate(
	    $qbAreas->getQuery(),
	    $this->get('request')->query->get('page', 1),
	    $cantidad
	 );
        return array(
            'areas' => $areas,
        );
    }
    
    /**
     * @Route("/areas/cerradas", name="busqueda_area_cerrada")
     * @Template("MagypRendicionDeCajaBundle:Area:cerradas.html.twig")
     */
    public function AreaCerradaAction()
    {
        //$idusuario = $this->getRequest()->get('usuario_id');
        $texto = $this->getRequest()->get('area_cerrada_buscar');
        $em = $this->getDoctrine()->getManager();
        $qbAreas = $em->getRepository('MagypRendicionDeCajaBundle:Area')->qb_areas_cerradas($texto);

	$paginador =  $this->get('knp_paginator');
	$cantidad = $this->get('request')->query->get('cant');
	$cantidad = intval($cantidad) > 0  ? intval($cantidad) : 10;
	
        $areas = $paginador->paginate(
	    $qbAreas->getQuery(),
	    $this->get('request')->query->get('page', 1),
	    $cantidad
	 );
        return array(
            'areas' => $areas,
        );
    }    
    
    /**
     * @Route("/area/lista", name="sistema_busqueda_area_lista")
     * @Template()
     */
    public function areaListaAction()
    {
        $em = $this->getDoctrine()->getManager();
        $areas = $em->getRepository('MagypRendicionDeCajaBundle:Area')->findBy( array ("borrado" => 0));
        return array('areas' => $areas);
    }  
    
    /**
     * @Route("/area/listacerrado", name="sistema_busqueda_area_listacerrado")
     * @Template("MagypRendicionDeCajaBundle:Busqueda:areaLista.html.twig")
     */
    public function areaListacerradoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $areas = $em->getRepository('MagypRendicionDeCajaBundle:Area')->findBy( array ("borrado" => 1));
        return array('areas' => $areas);
    }  
    
    /**
     * @Route("/notificaciones", name="busqueda_notificacion")
     * @Route("/notificaciones/{tipo}", name="busqueda_notificacion_tipo")
     * @Template("MagypRendicionDeCajaBundle:Notificacion:propias.html.twig")
     */
    public function NotificacionesAction($tipo = "entrantes", $pagina=1, $cantxpaginas= 20)
    {
		$em = $this->getDoctrine()->getManager();	
        $texto = $this->getRequest()->get('notificacion_buscar');
        
		if($tipo == "entrantes"){
			$qbnotificaciones = $em->getRepository('MagypRendicionDeCajaBundle:Notificacion')->qbNotificacionesEntrantes($this->getUsuario(), $texto);
		}
		if($tipo == "salientes"){
			$qbnotificaciones = $em->getRepository('MagypRendicionDeCajaBundle:Notificacion')->qbNotificacionesSalientes($this->getUsuario(), $texto);
		}

		$paginador =  $this->get('knp_paginator');
		$cantidad = $this->get('request')->query->get('cant');
		$cantidad = intval($cantidad) > 0  ? intval($cantidad) : 10;

		$notificaciones = $paginador->paginate(
			$qbnotificaciones->getQuery(),
			$this->get('request')->query->get('page', 1),
			$cantxpaginas
		 );
		$area = $this->getUsuario()->getArea();
		return array('notificaciones' => $notificaciones,'area' => $area,'tipo' => $tipo);
    }     
	
    /**
     *
     * @Route("/rendicion", name="sistema_busqueda_rendicion")
     * @Template()
     */
    public function rendicionAction()
    {
		
		$em = $this->getDoctrine()->getManager();
		$texto = $this->getRequest()->get('rendicion_buscar');
		$qbrendicones = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->qb_Buscar($texto);
		
		$paginador =  $this->get('knp_paginator');
		$cantidad = $this->get('request')->query->get('cantidad');
		$cantidad = intval($cantidad) > 0  ? intval($cantidad) : 10;

		$rendiciones = $paginador->paginate(
			$qbrendicones->getQuery(),
			$this->get('request')->query->get('page', 1),
			$cantidad
		 );
        return array('rendiciones' => $rendiciones,'textobuscar' =>$texto);
    }	
}
