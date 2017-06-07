<?php

namespace Magyp\RendicionDeCajaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Symfony\Component\HttpFoundation\Response;
use \Magyp\RendicionDeCajaBundle\Entity\Estado;
use \Magyp\RendicionDeCajaBundle\Entity\Notificacion;
use Magyp\RendicionDeCajaBundle\Entity\Rendicion;
use \Magyp\RendicionDeCajaBundle\Entity\Archivar;

/**
 * @Route("/sistema/estado", name="sistema_estado_index")
 * 
 */
class EstadoController extends BaseCofraController
{

    /**
     * @Route("/", name="sistema_estado_index")
     * @Template()
     */
    public function indexAction()
    {
	
	return array();
    }
   
    /**
     * @Route("/enviado/{idrendicion}", name="sistema_estado_rendicion_enviada")
     * @Template()
     */
    public function envidadaAction($idrendicion)
    {
	$em = $this->getDoctrine()->getManager();
	$repo = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion');
	//$rendicion = new Rendicion();
	/* @var Rendicion $rendicion */
	$rendicion = $repo->find($idrendicion);
	$rendicionAnterior = clone $rendicion;
        $aeRendicion= $repo->findBy(array ( 'borrado' => 0 ));
        $bEstaRepetido= false;
        foreach ($aeRendicion as $eRendicion) {
            if ( ($eRendicion->getExpediente() == $rendicion->getExpediente() ) &&
                ($eRendicion->getId() != $rendicion->getId() ) &&
                ( ($eRendicion->getEstado() == Estado::ACEPTADO ) || ($eRendicion->getEstado() == Estado::ENVIADO )
                    || ($eRendicion->getEstado() == Estado::ARCHIVADA ) || ($eRendicion->getEstado() == Estado::ATESORERIA )) )
                {
                $bEstaRepetido= true;
            }
        }
        
        if ( $bEstaRepetido == false ){
            $bEstaSincomprobantes = $em->getRepository('MagypRendicionDeCajaBundle:Comprobante')->tieneActivos($idrendicion);
            if ($bEstaSincomprobantes == false ){
                $estado = new Estado($this->getRequest());
                $rendicion->setEstado(Estado::ENVIADO);
                $this->getCofra()->addEventoRendicion(\Magyp\RendicionDeCajaBundle\Entity\Evento::CAMBIODEESTADO, $rendicion, $rendicionAnterior);
                $estado->mensajeEnviado();	    // se generan mensajes 
                $notificacion = new Notificacion($this->getUsuario(),$rendicion );
                $notificacion->setAsunto("Expediente Enviado");
                $notificacion->setContenido("Rendicion: " . $rendicion->getExpedienteCompleto() );
                $af = $em->getRepository('MagypRendicionDeCajaBundle:Area')->getAF();
                //echo get_class($af);

                $notificacion->setDestino($af);	
                $notificacion->setLink($this->generateUrl('af_rendicion', array('idrendicion' => $idrendicion)), false);
                //$notificacion->paraAF();	// destino por constante.
                $em->persist($notificacion);
                $em->persist($rendicion);
                $em->flush();
            }else{
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                $mensaje->setMensaje('Accion','No se puede enviar. No posee comprobantes cargados.')		    
                ->Error()
                ->Generar();
            }
        }else{
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
            $mensaje->setMensaje('Accion','No se puede enviar, el numero de expediente ya se encuentra en circulacion.')		    
                ->Error()
                ->Generar();
        }
	return $this->redirect($this->generateUrl('sistema_rendicion_detalle', array('idrendicion' => $idrendicion)));
	
    }
    
    /**
     * @Route("/aceptar/{idrendicion}", name="sistema_estado_rendicion_aceptar")
     * @Template()
     */
    public function aceptarAction($idrendicion)
    {
	$em = $this->getDoctrine()->getManager();
	$repo = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion');
	//$rendicion = new Rendicion();
	/* @var Rendicion $rendicion */
	$rendicion = $repo->find($idrendicion);
        
        if ( $rendicion->isEnviado()){
            $rendicionAnterior = clone $rendicion;
            $estado = new Estado($this->getRequest());
            $rendicion->setEstado(Estado::ACEPTADO);
            $this->getCofra()->addEventoRendicion(\Magyp\RendicionDeCajaBundle\Entity\Evento::CAMBIODEESTADO, $rendicion, $rendicionAnterior);
            $estado->mensajeAceptado();	    
            $notificacion = new Notificacion($this->getUsuario(), $rendicion);
            $notificacion->setAsunto("Expediente Aceptado");
            $notificacion->setContenido("Rendicion: " . $rendicion->getExpedienteCompleto() );	
            $notificacion->setDestino($rendicion->getArea());	
            $notificacion->setLink($this->generateUrl('sistema_rendicion_detalle', array('idrendicion' => $idrendicion)), false);
            //$notificacion->paraAF();	// destino por constante.
            $em->persist($notificacion);
            $em->persist($rendicion);
            $em->flush();
	
        
         }else{
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
            $mensaje->setMensaje('Accion','La rendicion tiene que estar en estado ENVIADA para poder ser ACEPTADA.')		    
                ->Error()
                ->Generar();
        }
	// redirigir al menu o pantalla de af.
	return $this->redirect($this->generateUrl('af_rendiciones_home'));
	
    }
    /**
     * @Route("/acorregir/{idrendicion}", name="sistema_estado_rendicion_acorregir")
     * @Template()
     */
    public function acorregirAction($idrendicion)
    {
	$em = $this->getDoctrine()->getManager();
	$repo = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion');
	//$rendicion = new Rendicion();
	/* @var Rendicion $rendicion */
	$rendicion = $repo->find($idrendicion);
        
        if ( $rendicion->isEnviado() || $rendicion->isAceptada()){
        
            $rendicionAnterior = clone $rendicion;
            $estado = new Estado($this->getRequest());
            $rendicion->setEstado(Estado::ACORREGIR);	
            $this->getCofra()->addEventoRendicion(\Magyp\RendicionDeCajaBundle\Entity\Evento::CAMBIODEESTADO, $rendicion, $rendicionAnterior);
            $estado->mensajeAcorregir();	    
            $notificacion = new Notificacion($this->getUsuario(),$rendicion);
            $notificacion->setAsunto("El Expediente Necesita Correcciones");
            $notificacion->setContenido("Rendicion:" . $rendicion->getExpedienteCompleto() );	
            $notificacion->setLink($this->generateUrl('sistema_rendicion_detalle', array('idrendicion' => $idrendicion)), false);
            $notificacion->setDestino($rendicion->getArea());	

            $em->persist($notificacion);
            $em->persist($rendicion);
            $em->flush();
            
            $message = \Swift_Message::newInstance()
						->setSubject('Cofra - Correcciones')
						->setFrom('cofra_noreply@magyp.gob.ar')
						->setReplyTo('cofra_noreply@magyp.gob.ar')
						->setTo($rendicion->getResponsable()->getEmail())
						->setBody(
						"Se necesitan correcciones en su rendicion con numero de expediente ".$rendicion->getExpediente());
            $this->get('mailer')->send($message);

            return $this->redirect($this->generateUrl('sistema_notificacion_motivo_nuevo', array('idnotificacion'=> $notificacion->getId(),'idrendicion' => $idrendicion)));
        }else{
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
            $mensaje->setMensaje('Accion','La rendicion tiene que estar en estado ENVIADA o ACEPTADA para poder ser enviada A CORREGIR.')		    
                ->Error()
                ->Generar();
        }
        return $this->redirect($this->generateUrl('af_rendiciones_home'));
	// redirigir al menu o pantalla de af.
	//return $this->redirect($this->generateUrl('af_rendiciones_enviadas'));
        // redirijo para agregarle el motivo y luego redirije a enviadas
	
	
    }
 
    
    /**
     * @Route("/atesoreria/{idrendicion}", name="sistema_estado_rendicion_atesoreria")
     * @Template()
     */
    public function atesoreriaAction($idrendicion)
    {
	$em = $this->getDoctrine()->getManager();
	$repo = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion');
	//$rendicion = new Rendicion();
	/* @var Rendicion $rendicion */
	$rendicion = $repo->find($idrendicion);
        if ( $rendicion->isAceptada()){
            $rendicionAnterior = clone $rendicion;
            $eLiquidacionAux = $em->getRepository('MagypRendicionDeCajaBundle:Liquidacion')->findOneBy(array ('rendicion' => $rendicion));

            if (empty ($eLiquidacionAux)){
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                $mensaje->setMensaje('Error',"No se puede enviar a tesoreria ya que no se genero ninguna liquidacion relacionada a esta rendicion.")		    
                        ->Error()->NoExpira()
                        ->Generar();
            }else{

                $estado = new Estado($this->getRequest());
                $rendicion->setEstado(Estado::ATESORERIA);
                            $this->getCofra()->addEventoRendicion(\Magyp\RendicionDeCajaBundle\Entity\Evento::CAMBIODEESTADO, $rendicion, $rendicionAnterior);
                $estado->mensajeAtesoreria();	    
           /*     $notificacion = new Notificacion($this->getUsuario());
                $notificacion->setAsunto("Expediente A TESORERIA");
                $notificacion->setContenido("Rendicion: " . $rendicion->getExpedienteCompleto() );	
                $notificacion->setDestino($rendicion->getArea());
                $notificacion->setLink($this->generateUrl('te_rendicion_detalle', array('idrendicion' => $idrendicion)), false);
                //$notificacion->paraAF();	// destino por constante.
                $em->persist($notificacion);*/
                $em->persist($rendicion);
                $em->flush();

                // redirigir al menu o pantalla de af.
                // redirijo para agregarle el motivo y luego redirije a enviadas
                //return $this->redirect($this->generateUrl('sistema_notificacion_motivo_nuevo', array('idnotificacion'=> $notificacion->getId(),'idrendicion' => $idrendicion)));
            }
        }else{
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
            $mensaje->setMensaje('Accion','La rendicion tiene que estar en estado ACEPTADA para poder ser enviada A TESORERIA.')		    
                ->Error()
                ->Generar();
        }
        return $this->redirect($this->generateUrl('af_rendiciones_home'));
    }
    
    
     /**
     * @Route("/archivada/{idrendicion}/{numerocaja}", name="sistema_estado_rendicion_archivada")
     * @Template()
     */
    public function archivadaAction($idrendicion, $numerocaja)
    {
	$em = $this->getDoctrine()->getManager();
	$repo = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion');
	//$rendicion = new Rendicion();
	/* @var Rendicion $rendicion */
	$rendicion = $repo->find($idrendicion);
        
        if ( $rendicion->isApagado()){
        
            $rendicionAnterior = clone $rendicion;
            $estado = new Estado($this->getRequest());
            $rendicion->setEstado(Estado::ARCHIVADA);
            $this->getCofra()->addEventoRendicion(\Magyp\RendicionDeCajaBundle\Entity\Evento::CAMBIODEESTADO, $rendicion, $rendicionAnterior);
            $estado->mensajeArchivada();	    
            /*$notificacion = new Notificacion($this->getUsuario());
            $notificacion->setAsunto("El Expediente Necesita Correcciones");
            $notificacion->setContenido("Rendicion:" . $rendicion->getExpedienteCompleto() );	
            $notificacion->setLink($this->generateUrl('sistema_rendicion_detalle', array('idrendicion' => $idrendicion)), false);
            $notificacion->setDestino($rendicion->getArea());	
            $em->persist($notificacion);
            */
            $em->persist($rendicion);

            $eArchivar = $em->getRepository("MagypRendicionDeCajaBundle:Archivar")->findOneBy( array ('rendicion'=> $rendicion ) );

            /*$eLiquidacion = $em->getRepository("MagypRendicionDeCajaBundle:Liquidacion")->find($idliquidacion);

            if ( $eLiquidacion->getRendicion() == $rendicion ){
*/
            $eArchivar->setRendicion($rendicion);
  //          $eArchivar->setLiquidacion($eLiquidacion);
            $eArchivar->setCaja($numerocaja);

            $em->persist($eArchivar);

                    $this->getCofra()->addEventoArchivar(\Magyp\RendicionDeCajaBundle\Entity\Evento::NUEVO, $eArchivar);


            $em->flush();

            // redirigir al menu o pantalla de af.

            // redirijo para agregarle el motivo y luego redirije a enviadas
            //return $this->redirect($this->generateUrl('sistema_notificacion_motivo_nuevo', array('idnotificacion'=> $notificacion->getId(),'idrendicion' => $idrendicion)));
            /*}else{
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                $mensaje->setMensaje('Error',"La liquidacion no se relacionada con la rendicion ingresada.")		    
                        ->Error()->NoExpira()
                        ->Generar();
            }*/
        
        }else{
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
            $mensaje->setMensaje('Accion','La rendicion tiene que estar en estado PAGADO para poder ser ARCHIVADA.')		    
                ->Error()
                ->Generar();
        }
        
        return $this->redirect($this->generateUrl('af_rendiciones_home'));
    }

    /**
     * @Route("/pantalla/af", name="sistema_estado_simulapantallaaf")
     * 
     */
    public function asdasdasAction($idrendicion)
    {
	
    }
    
      /**
     * @Route("/apagar/{idrendicion}/{idliquidacion}", name="sistema_estado_rendicion_apagar")
     * @Template()
     */
    public function apagarAction($idrendicion, $idliquidacion)
    {
	$em = $this->getDoctrine()->getManager();
	$repo = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion');
	//$rendicion = new Rendicion();
	/* @var Rendicion $rendicion */
	$rendicion = $repo->find($idrendicion);
        
        if ( $rendicion->isAtesoreria()){
        
            $rendicionAnterior = clone $rendicion;
            $estado = new Estado($this->getRequest());
            $rendicion->setEstado(Estado::APAGAR);
            $this->getCofra()->addEventoRendicion(\Magyp\RendicionDeCajaBundle\Entity\Evento::CAMBIODEESTADO, $rendicion, $rendicionAnterior);
            $estado->mensajeAapagar();	    
            $em->persist($rendicion);

            $eArchivar  = new Archivar();

            $eLiquidacion = $em->getRepository("MagypRendicionDeCajaBundle:Liquidacion")->find($idliquidacion);

            if ( $eLiquidacion->getRendicion() == $rendicion ){

                $eArchivar->setRendicion($rendicion);
                $eArchivar->setLiquidacion($eLiquidacion);

                $em->persist($eArchivar);
                $this->getCofra()->addEventoArchivar(\Magyp\RendicionDeCajaBundle\Entity\Evento::NUEVO, $eArchivar);
                
                $notificacion = new Notificacion($this->getUsuario(), $rendicion);
                $notificacion->setAsunto("Expediente A Pagar");
                $notificacion->setContenido("Rendicion: " . $rendicion->getExpedienteCompleto() );	
                $notificacion->setDestino($rendicion->getArea());
                $notificacion->setLink($this->generateUrl('te_rendicion_detalle', array('idrendicion' => $idrendicion)), false);
                //$notificacion->paraAF();	// destino por constante.
                $em->persist($notificacion);
                
                
                $em->flush();
                
                $message = \Swift_Message::newInstance()
                                     ->setSubject('Cofra - Firma')
                                     ->setFrom('cofra_noreply@magyp.gob.ar')
                                     ->setReplyTo('cofra_noreply@magyp.gob.ar')
                                     ->setTo($rendicion->getArea()->getResponsable()->getEmail())
                                     ->setBody(
                                     "Se solicita su presencia en el area de Tesoreria para firmar el pagare correspondiente a su expediente N° ".$rendicion->getExpediente()." de caja chica.");
                $this->get('mailer')->send($message);

           }else{
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                $mensaje->setMensaje('Error',"La liquidacion no se relacionada con la rendicion ingresada.")		    
                        ->Error()->NoExpira()
                        ->Generar();
            }
        
        }else{
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
            $mensaje->setMensaje('Accion','La rendicion tiene que estar en estado A TESORERIA para poder enviar la notificacion al responsable para que asista a firmar.')		    
                ->Error()
                ->Generar();
        }
        
        return $this->redirect($this->generateUrl('te_home'));
    }
    
     /**
     * @Route("/pagado/{idrendicion}", name="sistema_estado_rendicion_pagado")
     * @Template()
     */
    public function pagadoAction($idrendicion)
    {
	$em = $this->getDoctrine()->getManager();
	$repo = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion');
	//$rendicion = new Rendicion();
	/* @var Rendicion $rendicion */
	$rendicion = $repo->find($idrendicion);
        
        if ( $rendicion->isAapagar()){
            $rendicionAnterior = clone $rendicion;
            $estado = new Estado($this->getRequest());
            $rendicion->setEstado(Estado::PAGADO);
            $this->getCofra()->addEventoRendicion(\Magyp\RendicionDeCajaBundle\Entity\Evento::CAMBIODEESTADO, $rendicion, $rendicionAnterior);
            $estado->mensajeApagado();	
            
            $notificacion = new Notificacion($this->getUsuario(), $rendicion);
            $notificacion->setAsunto("Expediente PAGADO");
            $notificacion->setContenido("Rendicion: " . $rendicion->getExpedienteCompleto() );	
            $notificacion->setDestino($rendicion->getArea());	
            $notificacion->setLink($this->generateUrl('te_rendicion_detalle', array('idrendicion' => $idrendicion)), false);
            //$notificacion->paraAF();	// destino por constante.
            $em->persist($notificacion);
            
            $em->persist($rendicion);
            $em->flush();
	
        
         }else{
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
            $mensaje->setMensaje('Accion','La rendicion tiene que estar en estado A PAGAR para poder ser PAGADA.')		    
                ->Error()
                ->Generar();
        }
	// redirigir al menu o pantalla de af.
	return $this->redirect($this->generateUrl('te_home'));
	
    }
    
}
