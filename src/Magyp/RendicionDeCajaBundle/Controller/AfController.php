<?php

namespace Magyp\RendicionDeCajaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Request;
use Magyp\RendicionDeCajaBundle\Form\RendicionType;
use Framework\GeneralBundle\Entity\CodigoDeBarras;
use Magyp\RendicionDeCajaBundle\Entity\Estado;
/**
 * Af controller.
 *
 * @Route("/sistema/af")
 */
class AfController extends BaseCofraController
{
    
    /**
     * @Route("/", name="af_home")
     * @Secure(roles="ROLE_AF")
     * @Template("MagypRendicionDeCajaBundle:Af:afhome.html.twig")
     */
    public function afAction()
    {	    	    	    
	 //   if(!$this->getUsuario()->perteneceAF()) return $this->getCofra()->PantallaError("Usted no pertenece al Area de Administracion Financiera");	   
	    return array();
    }
    
            
    /**
     * @Route("/pip", name="af_pip_leer")
     * @Secure(roles="ROLE_AF")
     * @Template("MagypRendicionDeCajaBundle:Af:pip.html.twig")
     */
    public function pipLeerAction()
    {	    
	    return array();
    }
    
    
    /**
     * @Route("/pipbuscar", name="af_pip_buscar")
     * @Secure(roles="ROLE_AF")
     * @Template("MagypRendicionDeCajaBundle:Af:pip.html.twig")
     */
    public function pipBuscarAction( Request $request )
    {	    
        $em = $this->getDoctrine()->getManager();
        $codigoDeBarrasPost= $request->request->get('codigodebarras');
     
        $codigoDeBarras= new codigodebarras();
        $bCodigoDeBarrasValido= $codigoDeBarras->esValidoCodigoDeBarras( $codigoDeBarrasPost);
        if ( $bCodigoDeBarrasValido ){

                $reporteTipo= substr ($codigoDeBarrasPost, 0, 2 );
                $nCantDigitos= substr ($codigoDeBarrasPost, 2, 1 );
                $idRendicion= substr ($codigoDeBarrasPost, 3, $nCantDigitos );
            if ( $reporteTipo <= 4 ){
                $eRendicion = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->find( $idRendicion );

                if (!$eRendicion){
                    $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
                    $mensaje->setMensaje('Error','El numero de rendicion es invalido.')		    
                            ->Error()->NoExpira()
                            ->Generar();
                    return $this->redirect($this->generateUrl('af_pip_leer') );
                }else{
                    if ( $eRendicion->isAceptada() ){
                        return $this->redirect($this->generateUrl('sistema_liquidacion', array( 'idrendicion'=> $idRendicion ) ) );
                    }else{
                        $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
                        $mensaje->setMensaje('Error','La rendicion cargada no esta en estado ACEPTADA, la misma esta en estado '.$eRendicion->getEstadoNombre().".")		    
                            ->Error()->NoExpira()
                            ->Generar();
                        return $this->redirect($this->generateUrl('af_pip_leer') );
                    }
                }
            }else{
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
                $mensaje->setMensaje('Error','La impresion no se corresponde con un tipo de impresion a la que se la puede liquidar.')		    
                        ->Error()->NoExpira()
                        ->Generar();
                return $this->redirect($this->generateUrl('af_pip_leer') );
            }
        }else{
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
            $mensaje->setMensaje('Error','El codigo de barras no se corresponde con una rendicion a liquidar.')		    
                    ->Error()->NoExpira()
                    ->Generar();
            return $this->redirect($this->generateUrl('af_pip_leer') );
        }
    }
    
    
    /**
    * @Route("/pantalla/notificaciones", name="af_notificaciones_home")
    * @Secure(roles="ROLE_AF")
    * @Template("MagypRendicionDeCajaBundle:Af:afnotificaciones.html.twig")
    */
    public function afnotificacionesAction()
    { 
        return array();
    }

    
    
    /**
     * @Route("/notificaciones", name="af_notificaciones")
     * @Route("/notificaciones/{tipo}", name="af_notificaciones_tipo")
     * @Secure(roles="ROLE_AF")
     * @Template()
     */
    public function notificacionesAction($tipo = "entrantes", $pagina=1)
    {
	$em = $this->getDoctrine()->getManager();	
	if($tipo == "entrantes"){
	    $qbnotificaciones = $em->getRepository('MagypRendicionDeCajaBundle:Notificacion')->qbNotificacionesEntrantesAF();
	}
	if($tipo == "salientes"){
	    $qbnotificaciones = $em->getRepository('MagypRendicionDeCajaBundle:Notificacion')->qbNotificacionesSalientesAF();
	}

	$paginador =  $this->get('knp_paginator');
	$cantidad = $this->get('request')->query->get('cantidad');
	$cantidad = intval($cantidad) > 0  ? intval($cantidad) : 20;

	$notificaciones = $paginador->paginate(
	    $qbnotificaciones,
	    $this->get('request')->query->get('page', 1)/*page number*/,
	    $cantidad
	 );
	$area = $this->getUsuario()->getArea();
	return array('notificaciones' => $notificaciones,'area' => $area,'tipo' => $tipo);

    }
    
    /**
     * @Route("/rendiciones/enviadas", name="af_rendiciones_enviadas")
     * @Secure(roles="ROLE_AF")
     * Template("MagypRendicionDeCajaBundle:Af:rendiciones.html_1.twig")
     * @Template()
     */
    public function enviadasAction()
    {
	$em = $this->getDoctrine()->getManager();
	$rendiciones = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->conEstadoEnviado();
	//$areas= $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->getAreasConExpedientesEnviados();
	$qblista = $em->getRepository('MagypRendicionDeCajaBundle:Area')->qb_AreasConExpedientesEnviados();
	
        $paginador =  $this->get('knp_paginator');
	$cantidad = $this->get('request')->query->get('cantidad');
	$cantidad = intval($cantidad) > 0  ? intval($cantidad) : 10;
	
        $lista = $paginador->paginate(
	    $qblista->getQuery(),
	    $this->get('request')->query->get('page', 1),
	    $cantidad
	 );
	return array('rendiciones' => $rendiciones, 'lista' => $lista);
	
    }

    /**
     * @Route("/rendiciones/aceptadas", name="af_rendiciones_aceptadas")
     * @Secure(roles="ROLE_AF")
     * @Template()
     */
    public function aceptadasAction()
    {
	$em = $this->getDoctrine()->getManager();
	$rendiciones = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->conEstadoAceptado();
	$qblista = $em->getRepository('MagypRendicionDeCajaBundle:Area')->qb_AreasConExpedientesAceptados();
        
        $paginador =  $this->get('knp_paginator');
	$cantidad = $this->get('request')->query->get('cantidad');
	$cantidad = intval($cantidad) > 0  ? intval($cantidad) : 10;
	
        $lista = $paginador->paginate(
	    $qblista->getQuery(),
	    $this->get('request')->query->get('page', 1),
	    $cantidad
	 );
        
	return array('rendiciones' => $rendiciones, 'lista' => $lista);
	
    }
    
    
     /**
     * @Route("/rendiciones/atesoreria", name="af_rendiciones_atesoreria")
     * @Secure(roles="ROLE_AF")
     * Template("MagypRendicionDeCajaBundle:Af:rendiciones.html.twig")
     * @Template()
     */
    public function atesoreriaAction()
    {
	$em = $this->getDoctrine()->getManager();
	$rendiciones = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->conEstadoAtesoreria();
	//$areas= $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->getAreasConExpedientesEnviados();
	$qblista = $em->getRepository('MagypRendicionDeCajaBundle:Area')->qb_AreasConExpedientesAtesoreria();
	
        $paginador =  $this->get('knp_paginator');
	$cantidad = $this->get('request')->query->get('cantidad');
	$cantidad = intval($cantidad) > 0  ? intval($cantidad) : 10;
	
        $lista = $paginador->paginate(
	    $qblista->getQuery(),
	    $this->get('request')->query->get('page', 1),
	    $cantidad
	 );
	return array('rendiciones' => $rendiciones, 'lista' => $lista);
	
    }
    
    
    /**
     * @Route("/rendiciones/archivada", name="af_rendiciones_archivadas")
     * @Secure(roles="ROLE_AF")
     * Template("MagypRendicionDeCajaBundle:Af:rendiciones.html.twig")
     * @Template()
     */
    public function archivadaAction()
    {
	$em = $this->getDoctrine()->getManager();
	$rendiciones = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->conEstadoArchivada();
	//$areas= $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->getAreasConExpedientesEnviados();
	$qblista = $em->getRepository('MagypRendicionDeCajaBundle:Area')->qb_AreasConExpedientesArchivada();
	
        $paginador =  $this->get('knp_paginator');
	$cantidad = $this->get('request')->query->get('cantidad');
	$cantidad = intval($cantidad) > 0  ? intval($cantidad) : 10;
	
        $lista = $paginador->paginate(
	    $qblista->getQuery(),
	    $this->get('request')->query->get('page', 1),
	    $cantidad
	 );
	return array('rendiciones' => $rendiciones, 'lista' => $lista);
	
    }
    
    
    
    /**
     * @Route("/rendicion/{idrendicion}", name="af_rendicion")
     * @Secure(roles="ROLE_AF")     
     * @Template()
     */
    public function rendicionAction($idrendicion){
	    return $this->redirect($this->generateUrl('sistema_rendicion_detalle', array('idrendicion' => $idrendicion)));
        //return RendicionController::detalleRendicionFuncion($this,$idrendicion, true);
    }
    
    
    /**
     * @Route("/buscadorgeneral", name="af_buscador_general_pip")
     * @Secure(roles="ROLE_AF")
     * @Template("MagypRendicionDeCajaBundle:Af:pipgeneral.html.twig")
     */
    public function BuscadorGeneralPipAction()
    {	    
	    return array();
    }
    
    /**
     * @Route("/buscar", name="af_buscador_general")
     * @Secure(roles="ROLE_AF")
     * @Template()
     */
    public function buscadorGeneralAction( Request $request )
    {	    
        $codigoPorPost= $request->request->get('codigodebarras');
        $codigoDeBarras= new codigodebarras();
        $bCodigoDeBarrasValido= $codigoDeBarras->esValidoCodigoDeBarras( $codigoPorPost);
        if ( $bCodigoDeBarrasValido ){         
            $reporteTipo= substr ($codigoPorPost, 0, 2 );
            $nCantDigitos= substr ($codigoPorPost, 2, 1 );
            $id= substr ($codigoPorPost, 3, $nCantDigitos );
            switch ($reporteTipo){
                case 1:
                case 2:
                case 3:
                case 4:
                    $cDestino= "sistema_rendicion_detalle";
                    $cCampoId= "idrendicion";
                    
                    $em = $this->getDoctrine()->getManager();
                    $eRendicion = $em->getRepository("MagypRendicionDeCajaBundle:Rendicion")->find($id);
                    $estado= $eRendicion->getEstado();
                    switch ($estado){
                        case Estado::ACEPTADO:
                        case Estado::ENVIADO:
                        case Estado::ATESORERIA:
                        case Estado::ARCHIVADA:
                            //no hace nada, ya que son los unicos estados en los cuales se puede observar la rendicion.
                            
                            //NOTA: se puede poner un envio de mail automatico intimando al usuario que envie la rendicion.
                        break;
                        case Estado::NUEVO:
                            $mensajeAEnviar= "La rendicion esta en estado NUEVO, solicite al area correspondiente que presione el boton ENVIAR.";
                            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                            $mensaje->setMensaje('Error',$mensajeAEnviar)		    
                            ->Error()->NoExpira()
                            ->Generar();
                            return $this->redirect($this->generateUrl('af_buscador_general_pip'));
                        break;
                        case Estado::ACORREGIR:
                            $mensajeAEnviar= "La rendicion esta en estado A CORREGIR, solicite al area correspondiente que presione el boton ENVIAR..";
                            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                            $mensaje->setMensaje('Error',$mensajeAEnviar)		    
                            ->Error()->NoExpira()
                            ->Generar();
                            return $this->redirect($this->generateUrl('af_buscador_general_pip'));
                        break;
                    
                        default:
                            die("Estado Desconocido");
                    }
                break;
                case 5:
                    $cDestino= "sistema_liquidacion_show";
                    
                    $em = $this->getDoctrine()->getManager();
                    $eLiquidacion = $em->getRepository("MagypRendicionDeCajaBundle:Liquidacion")->find($id);
                    $idRendicion= $eLiquidacion->getRendicion()->getId();
                    return $this->redirect($this->generateUrl($cDestino, array ('id' => $id, 'idrendicion' => $idRendicion )));
                break;
                case 6:
                    $cDestino= "af_anticipodegasto_show";
                    $cCampoId= "id";
                break;                
                case 7:
                    $cDestino= "af_fondorotatorio_show";
                    $cCampoId= "id";
                break;
                case 8:
                    $cDestino= "af_reintegrodegasto_show";
                    $cCampoId= "id";
                break;                
                default:
                    $mensajeAEnviar= "Tipo de reporte invalido.";
                    $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                    $mensaje->setMensaje('Error',$mensajeAEnviar)		    
                    ->Error()->NoExpira()
                    ->Generar();
                    return $this->redirect($this->generateUrl('af_buscador_general_pip'));
                break;
            }
            return $this->redirect($this->generateUrl($cDestino, array ( $cCampoId => $id )));
            
        }else{
            $mensajeAEnviar= "Codigo de barras invalido.";
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
            $mensaje->setMensaje('Error',$mensajeAEnviar)		    
            ->Error()->NoExpira()
            ->Generar();
            return $this->redirect($this->generateUrl('af_buscador_general_pip'));
        }
    }
    
    /**
     * @Route("/pantalla/notas", name="af_notasgenericas_home")
     * @Secure(roles="ROLE_AF")
     * @Template("MagypRendicionDeCajaBundle:Af:afnotasgenericas.html.twig")
     */
    public function notasgenericasAction()
    {	    
	    return array();
    }
    
    /**
     * @Route("/pantalla/rendiciones", name="af_rendiciones_home")
     * @Secure(roles="ROLE_AF")
     * @Template("MagypRendicionDeCajaBundle:Af:afrendiciones.html.twig")
     */
    public function afrendicionesAction()
    {	    
	    return array();
    }
    
    /**
     * @Route("/pantalla/administracion", name="af_administracion_home")
     * @Secure(roles="ROLE_AF")
     * @Template("MagypRendicionDeCajaBundle:Af:afadministracion.html.twig")
     */
    public function afadministracionAction()
    {	    
	    return array();
    }
    
    /**
    * @Route("/rendicionliquidacion", name="af_rendicion_liquidacion")
    * @Secure(roles="ROLE_AF")
    * @Template("MagypRendicionDeCajaBundle:Af:rendicionliquidacion.html.twig")
    */
    public function afRendicionLiquidacionAction()
    { 
        //$em = $this->getDoctrine()->getManager();
        //$aeRendicion = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->findBy(array( 'borrado'=> 0 ), array('expediente' => 'DESC'));
        //return array('rendiciones' => $aeRendicion );
        
        //$aeArchivar = $em->getRepository('MagypRendicionDeCajaBundle:Archivar')->findAll();

        $em = $this->getDoctrine()->getManager();
        $texto = $this->getRequest()->get('rendicion_buscar');
       // $qbRendicionLiquidacion = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->RendicionesBuscadorLiquidaciones($texto);
        $qbRendicionLiquidacion = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->RendicionesConLiquidacion($texto);

	//	var_dump(count($qbRendicionLiquidacion->getQuery()->getResult()));
	//	var_dump($qbRendicionLiquidacion->getQuery()->getResult());
		$paginador =  $this->get('knp_paginator');
		$cantidad = $this->get('request')->query->get('cantidad');
		$cantidad = intval($cantidad) > 0  ? intval($cantidad) : 10;
	
        $aeRendicion = $paginador->paginate(
			$qbRendicionLiquidacion->getQuery(),
			$this->get('request')->query->get('page', 1),
			$cantidad
		 );
        		
		
        return array(
            'rendiciones' => $aeRendicion,
        );
        
        
    }
    
    /**
     * @Route("/rendiciones/pagado", name="sistema_rendiciones_pagado")
     * Secure(roles="ROLE_AF")
     * @Template()
     */
    public function pagadoAction()
    {
	$em = $this->getDoctrine()->getManager();
	$rendiciones = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->conEstadoApagado();
	//$areas= $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->getAreasConExpedientesEnviados();
	$qblista = $em->getRepository('MagypRendicionDeCajaBundle:Area')->qb_AreasConExpedientesApagado();
	
        $paginador =  $this->get('knp_paginator');
	$cantidad = $this->get('request')->query->get('cantidad');
	$cantidad = intval($cantidad) > 0  ? intval($cantidad) : 10;
	
        $lista = $paginador->paginate(
	    $qblista->getQuery(),
	    $this->get('request')->query->get('page', 1),
	    $cantidad
	 );
	return array('rendiciones' => $rendiciones, 'lista' => $lista);
	
    }
}
