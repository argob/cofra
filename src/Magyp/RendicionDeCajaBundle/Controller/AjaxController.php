<?php

namespace Magyp\RendicionDeCajaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * Ajax controller.
 *
 * @Route("/sistema/ajax")
 */
class AjaxController extends BaseCofraController
{
    /**
     * @Route("/enviadas/area/{idarea}", name="sistema_rendicion_enviadas_area")    
     * @Template()
     */
    public function enviadasAction($idarea)
    {    
	$em = $this->getDoctrine()->getManager();
	$rendiciones = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->conEstadoEnviado($idarea);
	return array('rendiciones' => $rendiciones);
    }
    /**
     * @Route("/aceptadas/area/{idarea}", name="sistema_rendicion_aceptadas_area")    
     * @Template()
     */
    public function aceptadasAction($idarea)
    {    
	$em = $this->getDoctrine()->getManager();
	$rendiciones = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->conEstadoAceptado($idarea);
	return array('rendiciones' => $rendiciones);
	}
	
    /**
     * @Route("/atesoreria/area/{idarea}", name="sistema_rendicion_atesoreria_area")    
     * @Template()
     */
    public function atesoreriaAction($idarea)
    {    
	$em = $this->getDoctrine()->getManager();
	$rendiciones = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->conEstadoAtesoreria($idarea);
	return array('rendiciones' => $rendiciones);
	}
        
        
     /**
     * @Route("/archivada/area/{idarea}", name="sistema_rendicion_archivada_area")    
     * @Template()
     */
    public function archivadaAction($idarea)
    {    
	$em = $this->getDoctrine()->getManager();
	$rendiciones = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->conEstadoArchivada($idarea);
        $cajas= array();
        foreach ($rendiciones as $rendicion) {
            $eArchivar = $em->getRepository("MagypRendicionDeCajaBundle:Archivar")->findOneBy(array('rendicion' => $rendicion->getId() ));
            $cajas[$rendicion->getId()]= $eArchivar->getCaja();
        }
        
        
	return array('rendiciones' => $rendiciones, 'cajas' => $cajas);
    }
        
        
    /**
     *
     * @Route("/referencia-imputacion/buscar", name="sistema_ajax_referencia_imputacion")
     * @Template()
     */
    public function referenciaAction()
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
	 * @Route("/referencia-imputacion", name="sistema_busqueda_imputacion_referencia_post")
	 * @Route("/referencia-imputacion/{palabras}", name="sistema_busqueda_imputacion_referencia_palabras")
     * @Template()
     */
    public function imputacionporReferenciasListaAction($palabras = null)
    {
		$em = $this->getDoctrine()->getManager();
		$palabrasclaves = $palabras;
		//$palabrasclaves = 'disco';
		$resp = $em->getRepository('MagypRendicionDeCajaBundle:Referenciaimputacion')->buscarReferencias($palabrasclaves);
		//$this->getCofra()->dump($resp,5);
		//var_dump($resp);
		$imputaciones = $resp[0];
		
		$referencias=$resp;
//		$imputaciones = array();
//		foreach ($resp as $imputacion){
//				$a
//		}
        //return array('imputaciones' => $imputaciones);
        return array('referencias' => $referencias,);
    }	
    
    
    /**
     * @Route("/aapagar/area/{idarea}", name="sistema_rendicion_aapagar_area")    
     * @Template()
     */
    public function aapagarAction($idarea)
    {    
	$em = $this->getDoctrine()->getManager();
	$rendiciones = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->conEstadoAapagar($idarea);
	return array('rendiciones' => $rendiciones);
    }
 
    /**
     * @Route("/apagado/area/{idarea}", name="sistema_rendicion_aapagado_area")    
     * @Template()
     */
    public function apagadoAction($idarea)
    {    
	$em = $this->getDoctrine()->getManager();
	$rendiciones = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->conEstadoApagado($idarea);
	return array('rendiciones' => $rendiciones);
    }
        
}
