<?php

namespace Magyp\RendicionDeCajaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\Liquidacion;
use Magyp\RendicionDeCajaBundle\Form\LiquidacionType;

use Magyp\RendicionDeCajaBundle\Entity\Programa;
use Magyp\RendicionDeCajaBundle\Form\ProgramaType;

use Magyp\RendicionDeCajaBundle\Entity\LiquidacionDetalle;
use Magyp\RendicionDeCajaBundle\Form\LiquidacionDetalleType;

use Magyp\RendicionDeCajaBundle\Entity\UbicacionGeografica;

use JMS\SecurityExtraBundle\Annotation\Secure;

use Magyp\RendicionDeCajaBundle\Entity\Rendicion;



/**
 * Liquidacion controller.
 *
 * @Route("sistema/af/liquidacion")
 */
class LiquidacionController extends BaseCofraController
{
    /**
     * Lists all Liquidacion entities.
     *
     * @Route("/id/{idrendicion}", name="sistema_liquidacion")
     * @Secure(roles="ROLE_AF")
     * @Template()
     */
    public function indexAction($idrendicion)
    {
        /* @var $eRendicion Rendicion */

        // $em = $this->getDoctrine()->getManager();
         $em = $this->getDoctrine()->getManager();
         //$em = $this->getDoctrine()->getManager();
         //$eLiquidacion = $em->getRepository('MagypRendicionDeCajaBundle:Liquidacion')->findBy( array('rendicion' => $idrendicion) );
         $aeLiquidacion = $em->getRepository('MagypRendicionDeCajaBundle:Liquidacion')->BuscarPorRendicion($idrendicion);
         $eRendicion = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->find( $idrendicion );

        foreach ($aeLiquidacion as $eLiquidacion  ){
             $eLiquidacion->setActividad(str_pad($eLiquidacion->getActividad(), 2, "0", STR_PAD_LEFT));
             $eLiquidacion->setUg(str_pad($eLiquidacion->getUg(), 2, "0", STR_PAD_LEFT));
        }
        $eLaArchivada = $em->getRepository('MagypRendicionDeCajaBundle:Archivar')->findOneBy(array ('rendicion' => $eRendicion));

        if ( empty($eLaArchivada) ){
            $idArchivada= 0;
        }else{
            $idArchivada= $eLaArchivada->getLiquidacion()->getId();
        }
        return array(
            'entities' => $aeLiquidacion,
            'idrendicion' => $idrendicion,
            'expediente' => $eRendicion->getExpedienteCompletoCorto(),
            'laarchivada' => $idArchivada
        );
    }

    /**
     * Finds and displays a Liquidacion entity.
     *
     * @Route("/{id}/{idrendicion}/mostrar", name="sistema_liquidacion_show")
     * @Secure(roles="ROLE_AF")
     * @Template()
     */
    public function mostrarAction($id, $idrendicion)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:Liquidacion')->find($id);

        $rendicion= $entity->getRendicion();
        $entity->setExpediente($rendicion->getExpedienteCompletoCorto());
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Liquidacion entity.');
        }


        $liquidacion = $em->getRepository("MagypRendicionDeCajaBundle:Liquidacion")->find($id);
        $hash= md5($liquidacion->getId().$liquidacion->getResponsable().$liquidacion->getArea());
        
        $aeLiquidacionDetalle= $em->getRepository('MagypRendicionDeCajaBundle:LiquidacionDetalle')->buscarDetallesDeLiquidacion( $id );
        
        foreach ($aeLiquidacionDetalle as $eLiquidacionDetalle ){
            $eLiquidacionDetalle->setPrograma(str_pad($eLiquidacionDetalle->getPrograma(), 2, "0", STR_PAD_LEFT));
        }
        
        $entity->setActividad(str_pad($entity->getActividad(), 2, "0", STR_PAD_LEFT));
        $entity->setUg(str_pad($entity->getUg(), 2, "0", STR_PAD_LEFT));    
        
        
        
        
        return array(
            'entity'      => $entity,
            'liquidaciondetalle' => $aeLiquidacionDetalle,
            'idrendicion' => $idrendicion,
            'hash' => $hash,
        );
    }

    /**
     * Displays a form to create a new Liquidacion entity.
     *
     * @Route("/nuevo/{idrendicion}", name="sistema_liquidacion_new")
     * @Secure(roles="ROLE_AF")
     * @Template()
     */
    public function nuevoAction($idrendicion)
    {
        $liquidacion = new Liquidacion();
        //$em = $this->getDoctrine()->getManager();
        $em = $this->getDoctrine()->getManager();
        $em->refresh($this->getUsuario());
        $rendicion = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->find($idrendicion);

        if ($this->validaQueEsteAceptada($rendicion) ){
        
            $comprobantes = $em->getRepository('MagypRendicionDeCajaBundle:Comprobante')->buscarActivosOrdenadosPorImputacionConSubTotal( $idrendicion );	

            $aFechaMinMax= $em->getRepository('MagypRendicionDeCajaBundle:Comprobante')->fechaMinMaxdeRendicion($idrendicion);

            //$total= $em->getRepository('MagypRendicionDeCajaBundle:Comprobante')->sumatoriaActivos($idrendicion);

            //$actividad= str_pad($rendicion->getArea()->getActividad(), 2, "0", STR_PAD_LEFT);
            $actividad= $rendicion->getArea()->getActividad();

            //$iniciales= substr( $rendicion->getResponsable()->getNombre(), 0, 1).substr( $this->getUsuario()->getApellido(), 0, 1);

            /*a completar cuando area este andando*/

            $fuenteFinanciamiento= 11;
            $ug = $em->getRepository('MagypRendicionDeCajaBundle:UbicacionGeografica')->find(1);

            /********************/
            $btieneResponsable= $rendicion->getArea()->getTieneResponsable();
            if ($btieneResponsable){
            
                $liquidacion->setActividad($actividad);
                $liquidacion->setArea($rendicion->getArea());
                $liquidacion->setExpediente($rendicion->getExpediente());
                $liquidacion->setFuentefinanciamiento($fuenteFinanciamiento);
                $liquidacion->setResponsable($this->getUsuario());
                $liquidacion->setNota("");
                $liquidacion->setPeriodofinal($aFechaMinMax['max']);
                $liquidacion->setPeriodoinicial($aFechaMinMax['min']);
                $liquidacion->setRendicion($rendicion);
                $liquidacion->setBeneficiario($rendicion->getArea()->getResponsable());
                //$liquidacion->setTotal($total[0][1]);
                $liquidacion->setUg($ug);

                //$formLiquidacion= $this->createForm(new LiquidacionType(), $liquidacion);

                //if ($formLiquidacion->isValid()) {
                    //$em = $this->getDoctrine()->getManager();
                    $em->persist($liquidacion);
                //}

                $pos= 0;
                foreach ($comprobantes as $comprobante ){

                    $detalle = new LiquidacionDetalle();
                    $detalle->setLiquidacion($liquidacion);
                    $detalle->setImportesubtotal($comprobante[1]);

                    $imputacion= $em->getRepository('MagypRendicionDeCajaBundle:Imputacion')->find( $comprobante['id'] );	

                    $detalle->setImputacion($imputacion);
                    //$detalle->setLiquidacion($liquidacion);
                    $detalle->setPrograma($rendicion->getArea()->getPrograma());

                    //$formDetalle= $this->createForm(new LiquidacionDetalleType(), $detalle);
                    //if ($formDetalle->isValid()) {
                        //$em = $this->getDoctrine()->getManager();
                        $em->persist($detalle);
                    //}
                    $pos++;
                }
                $this->getCofra()->addEventoLiquidacion(\Magyp\RendicionDeCajaBundle\Entity\Evento::NUEVO, $liquidacion);
                $em->flush();

                return $this->redirect($this->generateUrl('sistema_liquidacion_edit', array('id'=> $liquidacion->getId() , 'idrendicion' => $idrendicion )));
            }else{
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                $mensaje->setMensaje('Error','No tiene responsable asociado al area.')		    
                        ->Error()->NoExpira()
                        ->Generar();
                return $this->redirect($this->generateUrl('sistema_liquidacion',array('idrendicion' => $idrendicion) ));
            }
        }else{
            return $this->redirect($this->generateUrl('sistema_liquidacion',array('idrendicion' => $idrendicion) ));
        }
    }

    /**
     * Creates a new Liquidacion entity.
     *
     * @Route("/create", name="sistema_liquidacion_create")
     * @Secure(roles="ROLE_AF")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:Liquidacion:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Liquidacion();
        $form = $this->createForm(new LiquidacionType(), $entity);
        $form->bind($request);
	
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('sistema_liquidacion_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Liquidacion entity.
     *
     * @Route("/{id}/{idrendicion}/modificar", name="sistema_liquidacion_edit")
     * @Secure(roles="ROLE_AF")
     * @Template()
     */
    public function modificarAction($id, $idrendicion)
    {
        $em = $this->getDoctrine()->getManager();
        $rendicionAux = $em->getRepository("MagypRendicionDeCajaBundle:Rendicion")->find($idrendicion);
        if ($this->validaQueEsteAceptada($rendicionAux) ){
        
            $liquidacion = $em->getRepository("MagypRendicionDeCajaBundle:Liquidacion")->find($id);

            $hash= md5($liquidacion->getId().$liquidacion->getResponsable().$liquidacion->getArea());

            $liquidacion->setUg(str_pad($liquidacion->getUg(), 2, "0", STR_PAD_LEFT));
            $liquidacion->setActividad(str_pad($liquidacion->getActividad(), 2, "0", STR_PAD_LEFT));

            //$detalles = $em->getRepository("MagypRendicionDeCajaBundle:LiquidacionDetalle")->findBy(array ('liquidacion' => $id) );
            $detalles= $em->getRepository("MagypRendicionDeCajaBundle:LiquidacionDetalle")->buscarDetallesDeLiquidacion($id);
            $formLiquidacion = $this->createForm(new LiquidacionType(), $liquidacion);

            $programas = $em->getRepository("MagypRendicionDeCajaBundle:Programa")->findAll();

            $rendicion= $liquidacion->getRendicion();

            if ($this->validaQueEsteAceptada($rendicion) ){

                $liquidacion->setExpediente($rendicion->getExpedienteCompletoCorto());

                $liquidacion->setArea($liquidacion->getRendicion()->getArea());
                $liquidacion->setBeneficiario($liquidacion->getRendicion()->getArea()->getResponsable());

                $resultado = array ( 'idrendicion' => $idrendicion,
                    'liquidacion' => $liquidacion,
                    'detalles' => $detalles,
                    'form_liquidacion'   => $formLiquidacion->createView(),
                    'programas' => $programas,
                    'hash' => $hash,
                    );

                return $resultado;
            }
        }else{
            return $this->redirect($this->generateUrl('sistema_liquidacion',array('idrendicion' => $idrendicion) ));
        }
    }

    /**
     * Edits an existing Liquidacion entity.
     *
     * @Route("/{idliquidacion}/{idrendicion}/update", name="sistema_liquidacion_update")
     * @Secure(roles="ROLE_AF")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:Liquidacion:edit.html.twig")
     */
    public function updateAction(Request $request, $idrendicion, $idliquidacion)
    {
        $em = $this->getDoctrine()->getManager();
        $em->refresh($this->getUsuario());
        $rendicionAux = $em->getRepository("MagypRendicionDeCajaBundle:Rendicion")->find($idrendicion);
        if ($this->validaQueEsteAceptada($rendicionAux) ){

            $id= $request->request->get('idliquidacion');
            $idrendicion= $request->request->get('idrendicion');
            $nota= $request->request->get('liquidacion_nota');
            $detalles= $request->request->get('programa_detalle');
            $detallesATransferir= $request->request->get('programa_detalle_trnasferir');
            $montoATransferir= $request->request->get('monto_detalle_transferir');
            $detallesABorrar= $request->request->get('borrar_detalle');
            //Traspasa subtotales segun el programa al que se divide
            $bPercistirDetalle= true;
            $bMostrarError= false;
            $cMensajeError= "";
            $nDetalleCont= 0;
            foreach ( $detalles as $idDetalle => $idPrograma){
                $nDetalleCont++;
                /* @var $eDetalle LiquidacionDetalle */
                $eDetalle = $em->getRepository('MagypRendicionDeCajaBundle:LiquidacionDetalle')->find($idDetalle);
                $ePrograma = $em->getRepository('MagypRendicionDeCajaBundle:Programa')->find($idPrograma);
                //cambia el programa directmanete desde el combo programa , NO TRASNFIERE
                if ( $eDetalle->getPrograma() != $ePrograma ){
                    $eDetalle->setPrograma( $ePrograma );
                    $em->persist($eDetalle);
                    $em->flush();
                }
                //Chequea que se compreten los campos programa y monto a pasar en trasnferir, si pasa esto siguie con la transferencia
                if ( ( $detallesATransferir[$idDetalle] != -1 ) && ( $montoATransferir[$idDetalle] != 0 ) ){
                    if ( $eDetalle->getImportesubtotal() >= $montoATransferir[$idDetalle] ){
                        if  ( isset ($detallesABorrar[$idDetalle]) == true ){
                            $cMensajeError.= "<strong>Detalle $nDetalleCont:</strong> No se puede Desahacer un detalle que al mismo tiempo se quiere transferir.</br></br>";

                            $bPercistirDetalle= false;
                            $bMostrarError= true;
                        }else{
                            $eProgramaATransferir = $em->getRepository('MagypRendicionDeCajaBundle:Programa')->find( $detallesATransferir[$idDetalle] );
                            foreach ( $detalles as $idDetalleBus => $idProgramaBus){

                                $eDetallebus = $em->getRepository('MagypRendicionDeCajaBundle:LiquidacionDetalle')->find($idDetalleBus);
                                $eDetalleATransferir = $em->getRepository('MagypRendicionDeCajaBundle:LiquidacionDetalle')->find($idDetalle);

                                if ( ( $eDetallebus->getPrograma() == $eProgramaATransferir ) 
                                        && ( $eDetallebus->getImputacion() == $eDetalleATransferir->getImputacion() )
                                        ){
                                    //die ("No se puede transferir a un programa el cual la imputacion ya esta asociada.");

                                    $cMensajeError.= "<strong>Detalle $nDetalleCont:</strong> No se puede transferir a un programa el cual la imputacion ya esta asociada.</br></br>";

                                    $bPercistirDetalle= false;
                                    $bMostrarError= true;
                                }
                            }

                            if ( $bPercistirDetalle == true ){//si tenes q volver a la validacion simple de priograma  y transferir borra esto
                                if ( $eDetalle->getImportesubtotal() - $montoATransferir[$idDetalle] == 0 ){
                                    $eDetalle->setImportesubtotal ( $montoATransferir[$idDetalle] );
                                }else{
                                    $eDetalle->setImportesubtotal( $eDetalle->getImportesubtotal() - $montoATransferir[$idDetalle] );
                                    $eDetalleNuevo = new LiquidacionDetalle();
                                    $eDetalleNuevo->setLiquidacion($em->getRepository('MagypRendicionDeCajaBundle:Liquidacion')->find($id));
                                    $eDetalleNuevo->setImputacion($eDetalle->getImputacion() );
                                    $eDetalleNuevo->setPrograma($em->getRepository('MagypRendicionDeCajaBundle:Programa')->find($detallesATransferir[$idDetalle]));
                                    $eDetalleNuevo->setImportesubtotal($montoATransferir[$idDetalle]);
                                    $em->persist($eDetalleNuevo);
                                    $em->flush();
                                }
                            }
                        }
                    }else{
                        $cMensajeError.= "<strong>Detalle $nDetalleCont:</strong> El monto a transferir es mayor al subtotal.</br></br>";
                        //die ("El monto a transferir es mayor al subtotal.");
                        $bPercistirDetalle= false;
                        $bMostrarError= true;
                    }
                }
                if ( $bPercistirDetalle == true ){
                    $em->persist($eDetalle);
                    $em->flush();
                }else{
                    $bPercistirDetalle= true;
                }
            }

            //borra un detalle, pero antes traspasa el dinero a borrar a la imputacion correspondiente
            if ( $detallesABorrar != null ){
                $nDetalleCont= 0;
                foreach ( $detallesABorrar as $idBorrar => $idBorrarValidador){
                    $nDetalleCont++;
                    if ( $idBorrarValidador == $idBorrar ){
                        /* @var $eDetalleBorrar LiquidacionDetalle */
                        $eDetalleBorrar = $em->getRepository('MagypRendicionDeCajaBundle:LiquidacionDetalle')->find($idBorrar);
                        $montoATraspasar= $eDetalleBorrar->getImportesubtotal();
                        $validaNoBorarLaImputacionPadre= false;//Hace que no se incremente dos veces el monto eliminado
                        $bIncrementarloAUnaSolaImputacion= true;
                        foreach ( $detalles as $idDetalle => $idPrograma){
                            if ($bIncrementarloAUnaSolaImputacion == true ){
                                $detallaAUpdatear= $em->getRepository('MagypRendicionDeCajaBundle:LiquidacionDetalle')->find($idDetalle);
                                if ( ( $detallaAUpdatear->getImputacion() ==  $eDetalleBorrar->getImputacion()  ) && ( $idDetalle != $eDetalleBorrar->getId() ) ){
                                    $detallaAUpdatear->setImportesubtotal( $detallaAUpdatear->getImportesubtotal() + $montoATraspasar );
                                    $em->persist($detallaAUpdatear);
                                    $em->flush();
                                    $validaNoBorarLaImputacionPadre= true;
                                    $bIncrementarloAUnaSolaImputacion= false;
                                }
                            }
                        }
                        if ( $validaNoBorarLaImputacionPadre == true ){
                            $em->remove($eDetalleBorrar);
                            $em->flush();
                        }else{
                            $cMensajeError.= "<strong>Detalle $nDetalleCont:</strong> No se puede borrar, ya que es el unico detalle asosiada a esa imputacion.</br></br>";
                            //die ("No se puede borrar, ya que es el unico detalle con esa asosiada a esa imputacion");
                            $bMostrarError= true;
                        }
                    }
                }
            }

            /* @var $eLiquidacion Liquidacion */
            $eLiquidacion = $em->getRepository('MagypRendicionDeCajaBundle:Liquidacion')->find($id);
            $liquidacionAnterior= clone $eLiquidacion;
            $eLiquidacion->setNota($nota);
            $eLiquidacion->setArea($eLiquidacion->getRendicion()->getArea());
            $eLiquidacion->setBeneficiario($eLiquidacion->getRendicion()->getArea()->getResponsable());
            $eLiquidacion->setResponsable($this->getUsuario());
            $eLiquidacion->setFecha(new \DateTime());
            /*if (!$entity) {
                throw $this->createNotFoundException('Unable to find Liquidacion entity.');
            }*/

            $em->persist($eLiquidacion);
            $this->getCofra()->addEventoLiquidacion(\Magyp\RendicionDeCajaBundle\Entity\Evento::MODIFICIACION, $eLiquidacion, $liquidacionAnterior);
            $em->flush();
            if ( $bMostrarError == true ){
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
                $mensaje->setMensaje('Error',$cMensajeError)		    
                        ->Error()->NoExpira()
                        ->Generar();
            }

            //return $this->redirect($this->generateUrl('imprimir_liquidacion', array('idliquidacion' => $id)));
            return $this->redirect($this->generateUrl('sistema_liquidacion_show', array('id' => $id, 'idrendicion' => $idrendicion )));
        }else{
            return $this->redirect($this->generateUrl('sistema_liquidacion',array('idrendicion' => $idrendicion) ));
        }
    }
    //---------------------------------------------------------------------------------------------------------------
    /**
     *
     * @Route("/borrar/{idliquidacion}", name="sistema_liquidacion_borrar")
     * @Secure(roles="ROLE_AF")
     */
    public function borrarAction($idliquidacion)
    {
        $em = $this->getDoctrine()->getManager();
        $em->refresh($this->getUsuario());
        $liquidacion = $em->getRepository('MagypRendicionDeCajaBundle:Liquidacion')->find($idliquidacion);    
        $archivar = $em->getRepository('MagypRendicionDeCajaBundle:Archivar')->findOneBy(array ('liquidacion' => $liquidacion ) );  
        if (count($archivar) != 0){
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
            $mensaje->setMensaje('Error',"No se puede borrar esta liquidacion, ya que la misma esta archivada.")		    
                    ->Error()->NoExpira()
                    ->Generar();
        }else{
            $liquidacion->setBorrado(true);
            $em->persist($liquidacion);
            $this->getCofra()->addEventoLiquidacion(\Magyp\RendicionDeCajaBundle\Entity\Evento::BORRAR, $liquidacion);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('sistema_liquidacion',array('idrendicion' => $liquidacion->getRendicion()->getId()) ));
        
    }   
    
    /**
     *
     * @Route("/rendicion/{idrendicion}/papelera", name="sistema_liquidacion_papelera")
     * @Secure(roles="ROLE_AF")
     * @Template()
     */
    public function papeleraAction($idrendicion)
    {
	$em = $this->getDoctrine()->getManager();
        $liquidaciones = $em->getRepository('MagypRendicionDeCajaBundle:Liquidacion')->borradas($idrendicion);        
	
        return array('liquidaciones' => $liquidaciones);
    }    
    /**
     *
     * @Route("/restaurar/{idliquidacion}", name="sistema_liquidacion_restaurar")
     * @Secure(roles="ROLE_AF")
     * @Template()
     */
    public function restaurarAction($idliquidacion)
    {
        $em = $this->getDoctrine()->getManager();
        $em->refresh($this->getUsuario());
        $liquidacion = $em->getRepository('MagypRendicionDeCajaBundle:Liquidacion')->find($idliquidacion);        
        $liquidacion->setBorrado(0);
	// evento
	$this->getCofra()->addEventoLiquidacion(\Magyp\RendicionDeCajaBundle\Entity\Evento::RESTAURAR, $liquidacion);
	
        $em->persist($liquidacion);
        $em->flush();
	return $this->redirect($this->generateUrl('sistema_liquidacion',array('idrendicion' => $liquidacion->getRendicion()->getId()) ));
    }   
    /**
     * @Route("/{idliquidacion}/{idrendicion}/eventos", name="sistema_liquidacion_eventos")
     * @Secure(roles="ROLE_LOG")
     * @Template()	
     */
    public function eventosAction($idliquidacion,$idrendicion)
    {
	$eventos = $this->getCofra()->getEventoLiquidacion($idliquidacion);
	
	return array('eventos' => $eventos, 'idrendicion' => $idrendicion);
        
    }    
    
    public function validaQueEsteAceptada($rendicion){
        if ( ! ($rendicion->isAceptada()) ){
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
            $mensaje->setMensaje('Error',"La rendicion tiene que estar en estado Aceptada para generar una liquidacion. La misma se encuentra en estado ".$rendicion->getEstadoNombre().".")		    
                    ->Error()->NoExpira()
                    ->Generar();
            return false;
        }else{
            return true;
        }
    }
    
}
