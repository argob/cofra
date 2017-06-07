<?php

namespace Magyp\RendicionDeCajaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\FondoRotatorio;
use Magyp\RendicionDeCajaBundle\Form\FondoRotatorioType;

use Magyp\RendicionDeCajaBundle\Entity\FondoRotatorioFactura;
use Magyp\RendicionDeCajaBundle\Entity\FondoRotatorioImputacion;
use Magyp\RendicionDeCajaBundle\Entity\FondoRotatorioRetencion;

use Magyp\RendicionDeCajaBundle\Entity\RetencionTipo;

use Magyp\RendicionDeCajaBundle\Form\RetencionTipoType;

use JMS\SecurityExtraBundle\Annotation\Secure;
/**
 * FondoRotatorio controller.
 *
 * @Route("/sistema/af/fondorotatorio")
 */
class FondoRotatorioController extends BaseCofraController
{
    
    /**
     * Lists all FondoRotatorio entities.
     *
     * @Route("/", name="af_fondorotatorio")
     * @Secure(roles="ROLE_AF")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        //$entities = $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorio')->findAll();

        $texto = $this->getRequest()->get('fondorotatorio_buscar');
        //$qb = $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorio')->qb_todas();
        $qb = $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorio')->qb_buscar($texto);
//        foreach ( $entities as $entity ){
//            $entity->setActividad(str_pad($entity->getActividad(), 2, "0", STR_PAD_LEFT));
//            $entity->setUg(str_pad($entity->getUg(), 2, "0", STR_PAD_LEFT));
//            $entity->setPrograma(str_pad($entity->getPrograma(), 2, "0", STR_PAD_LEFT));
//        }
             
        $paginador =  $this->get('knp_paginator');
	$cantidad = $this->get('request')->query->get('cantidad');
	$cantidad = intval($cantidad) > 0  ? intval($cantidad) : 5;
	$paginador = $paginador->paginate(
	    $qb->getQuery(),
	    $this->get('request')->query->get('page', 1),
	    $cantidad
	 );
	
	$entities = $paginador;        
        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a FondoRotatorio entity.
     *
     * @Route("/{id}/mostrar", name="af_fondorotatorio_show")
     * @Secure(roles="ROLE_AF")
     * @Template()
     */
    public function mostrarAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $eFondoRotatorio = $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorio')->find($id);

        if (!$eFondoRotatorio) {
            throw $this->createNotFoundException('Unable to find FondoRotatorio entity.');
        }
        
        $eFondoRotatorioFactura = $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorioFactura')->findBy(array ('fondorotatorio' => $id));
        
        $aeFondoRotatorioFacturaRetencion= $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorioRetencion')->findBy(array ('fondorotatorio' => $id ) );
        $aRetencionesTipo= array();
        $aRetencionesTipoMonto= array();
        $nPosRetTipo= 0;
        foreach ($aeFondoRotatorioFacturaRetencion as $eFondoRotatorioFacturaRetencion){
            $aRetencionesTipo[$nPosRetTipo]= $eFondoRotatorioFacturaRetencion->getRetenciontipo()->getDescripcion();
            $aRetencionesTipoMonto= $eFondoRotatorioFacturaRetencion->getMonto();
            $nPosRetTipo++;
        }
        
        $eFondoRotatorioImputaciones = $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorioImputacion')->findBy(array ('fondorotatorio' => $id));

        $deleteForm = $this->createDeleteForm($id);
        $hash= md5($eFondoRotatorio->getId().$eFondoRotatorio->getExpediente().$eFondoRotatorio->getArea());

        $eFondoRotatorio->setActividad(str_pad($eFondoRotatorio->getActividad(), 2, "0", STR_PAD_LEFT));
        $eFondoRotatorio->setUg(str_pad($eFondoRotatorio->getUg(), 2, "0", STR_PAD_LEFT));
        $eFondoRotatorio->setPrograma(str_pad($eFondoRotatorio->getPrograma(), 2, "0", STR_PAD_LEFT));
        
        return array(
            'fondorotatorio'      => $eFondoRotatorio,
            'fondorotatoriofacturas'      => $eFondoRotatorioFactura,
            'fondorotatorioimputaciones' => $eFondoRotatorioImputaciones,
            'retencionestipos'  => $aeFondoRotatorioFacturaRetencion,
            'delete_form' => $deleteForm->createView(),
            'hash' => $hash,
        );
    }

    /**
     * Displays a form to create a new FondoRotatorio entity.
     *
     * @Route("/nuevo", name="af_fondorotatorio_new")
     * @Secure(roles="ROLE_AF")
     * @Template()
     */
    public function nuevoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new FondoRotatorio();
        $form   = $this->createForm(new FondoRotatorioType(), $entity);

        $eRetencionTipo = $em->getRepository("MagypRendicionDeCajaBundle:RetencionTipo")->findAll();
        
        $eImputaciones = $em->getRepository("MagypRendicionDeCajaBundle:Imputacion")->buscarImputaciones();
        
        $meTipoFactura= $em->getRepository("MagypRendicionDeCajaBundle:TipoFactura")->findAll();
        
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'retenciontipos' => $eRetencionTipo,
            'imputaciones' => $eImputaciones,
            'tipofacturas' => $meTipoFactura,
        );
    }

    /**
     * Creates a new FondoRotatorio entity.
     *
     * @Route("/crear", name="af_fondorotatorio_create")
     * @Secure(roles="ROLE_AF")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:FondoRotatorio:nuevo.html.twig")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $postData= $request->request->all();
        if (isset($postData['retenciontipofactura'])){
            $aeRetencionTipos= $postData['retenciontipofactura'];
        }
            $aeNumerosFactura= $postData['numerofactura'];
            $aeMontoFacturas= $postData['montofactura'];
            $aeImputaciones= $postData['imputacion'];
            $aeMontoImputaciones= $postData['montoimputacion'];

            $montoIva= $postData['montoiva'];
            $montoGanancia= $postData['montoganancia'];
            $montoResolucion= $postData['montoresolucion'];

            $aTipofactura= $postData['tipofactura'];

            $eFondoRotatorio  = new FondoRotatorio();
            $form = $this->createForm(new FondoRotatorioType(), $eFondoRotatorio);
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $userCompleto = $this->getDoctrine()->getManager()->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($this->getUsuario()->getId());
                $em->refresh($userCompleto);
                $eFondoRotatorio->setResponsable($userCompleto);
                $em->persist($eFondoRotatorio);
                //$em->flush();

                for ( $nPosTipoFactura= 0 ; $nPosTipoFactura < count ( $aeRetencionTipos ) ; $nPosTipoFactura++ ){
                    $eFondoRotatorioFacturaRetencion= new FondoRotatorioRetencion();
                    $eRetencionTipo = $em->getRepository('MagypRendicionDeCajaBundle:RetencionTipo')->find( $aeRetencionTipos[$nPosTipoFactura] );
                    $eFondoRotatorioFacturaRetencion->setRetenciontipo($eRetencionTipo);
                    $eFondoRotatorioFacturaRetencion->setFondorotatorio($eFondoRotatorio);
                    switch ($eRetencionTipo->getId()){
                        case 1:
                            $eFondoRotatorioFacturaRetencion->setMonto($montoIva);
                        break;
                        case 2:
                            $eFondoRotatorioFacturaRetencion->setMonto($montoGanancia);
                        break;
                        case 3:
                            $eFondoRotatorioFacturaRetencion->setMonto($montoResolucion);
                        break;
                        default:
                            die ("tipo incorrecto de retencion");
                    }

                    $em->persist($eFondoRotatorioFacturaRetencion);
                }

                for ( $nPosFactura= 0 ; $nPosFactura < count ($aeNumerosFactura) ; $nPosFactura++){

                    $eFondoRotatorioFactura= new FondoRotatorioFactura();
                    $eFondoRotatorioFactura->setNumero($aeNumerosFactura[$nPosFactura]);
                    $eFondoRotatorioFactura->setMonto($aeMontoFacturas[$nPosFactura]);
                    $eFondoRotatorioFactura->setFondoRotatorio($eFondoRotatorio);

                    $eTipoFactura = $em->getRepository('MagypRendicionDeCajaBundle:TipoFactura')->find( $aTipofactura[$nPosFactura] );
                    $eFondoRotatorioFactura->setTipofactura($eTipoFactura);

                    $em->persist($eFondoRotatorioFactura);
                }

                for ( $nPosImputacion= 0 ; $nPosImputacion < count ( $aeMontoImputaciones ) ; $nPosImputacion++){
                    $eFondoRotatorioImputacion= new FondoRotatorioImputacion();
                    $eImputacion = $em->getRepository('MagypRendicionDeCajaBundle:Imputacion')->find( $aeImputaciones[$nPosImputacion] );
                    $eFondoRotatorioImputacion->setImputacion($eImputacion);
                    $eFondoRotatorioImputacion->setMonto($aeMontoImputaciones[$nPosImputacion]);

                    $eFondoRotatorioImputacion->setFondoRotatorio($eFondoRotatorio);
                    $em->persist($eFondoRotatorioImputacion);
                }

                $em->flush();
                $cMensajeError= "Correcto.";
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
                $mensaje->setMensaje('Exito', $cMensajeError)		    
                    ->Exito()
                    ->Generar();
                return $this->redirect($this->generateUrl('af_fondorotatorio_show', array('id' => $eFondoRotatorio->getId() )));
            }
        else{
            return $this->redirect($this->generateUrl('af_fondorotatorio_new'));
        }
    }

    /**
     * Displays a form to edit an existing FondoRotatorio entity.
     *
     * @Route("/{id}/modificar", name="af_fondorotatorio_edit")
     * @Secure(roles="ROLE_AF")
     * @Template()
     */
    public function modificarAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $eFondoRotatorio= $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorio')->find($id);

        $aeFondoRotatorioFactura= $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorioFactura')->findBy(array ('fondorotatorio' => $id ) );
        $aeFondoRotatorioImputacion= $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorioImputacion')->findBy(array ('fondorotatorio' => $id ) );
        $aeFondoRotatorioFacturaRetencion= $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorioRetencion')->findBy(array ('fondorotatorio' => $id ) );
 
        if (!$eFondoRotatorio) {
            throw $this->createNotFoundException('Unable to find FondoRotatorio entity.');
        }

        $editForm = $this->createForm( new FondoRotatorioType(), $eFondoRotatorio );
        $deleteForm = $this->createDeleteForm($id);

        $eRetencionTipo = $em->getRepository("MagypRendicionDeCajaBundle:RetencionTipo")->findAll();
        $eImputaciones = $em->getRepository("MagypRendicionDeCajaBundle:Imputacion")->buscarImputaciones();
        $aeTipoFactura= $em->getRepository("MagypRendicionDeCajaBundle:TipoFactura")->findAll();
        $aRetencionesId= array();
        $nPosRetencionesArray= 0;
        
        $ivaDisplay= "none";
        $gananciaDisplay= "none";
        $resolucionDisplay= "none";
        
        $ivaMonto= 0;
        $gananciaMonto= 0;
        $resolucionMonto= 0;
        
        foreach ($aeFondoRotatorioFacturaRetencion as $eFondoRotatorioFacturaRetencion){
            $aRetencionesId[$nPosRetencionesArray]= $eFondoRotatorioFacturaRetencion->getRetenciontipo()->getId();
            
            switch ($aRetencionesId[$nPosRetencionesArray]){
                case 1:
                    $ivaMonto= $eFondoRotatorioFacturaRetencion->getMonto();
                    $ivaDisplay= "inline";
                break;
                case 2:
                    $gananciaMonto= $eFondoRotatorioFacturaRetencion->getMonto();
                    $gananciaDisplay= "inline";
                break;
                case 3:
                    $resolucionMonto= $eFondoRotatorioFacturaRetencion->getMonto();
                    $resolucionDisplay= "inline";
                break;
                default:
                    die("tipo de retencion desconocida");
            }
            
            $nPosRetencionesArray++;
        }
        
        return array(
            'entity'      => $eFondoRotatorio,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'fondorotatoriofacturas'     => $aeFondoRotatorioFactura,
            'fondorotatorioimputaciones'  => $aeFondoRotatorioImputacion,
            'fondorotatorioretenciones' => $aRetencionesId,
            'retenciontipos' => $eRetencionTipo,
            'imputaciones' => $eImputaciones,
            'tipofacturas' => $aeTipoFactura,
            
            'ivadisplay' => $ivaDisplay,
            'gananciadisplay' => $gananciaDisplay,
            'resoluciondisplay' => $resolucionDisplay,

            'ivamonto' => $ivaMonto,
            'gananciamonto' => $gananciaMonto,
            'resolucionmonto' => $resolucionMonto,
        );
    }

    /**
     * Edits an existing FondoRotatorio entity.
     *
     * @Route("/{id}/update", name="af_fondorotatorio_update")
     * @Secure(roles="ROLE_AF")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:FondoRotatorio:modificar.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $postData= $request->request->all();
      
        //var_dump ($postData);
        //die ("");
   
        $montoIva= $postData['montoiva'];
        $montoGanancia= $postData['montoganancia'];
        $montoResolucion= $postData['montoresolucion'];
        
        $aTipofactura= $postData['tipofactura'];
        
        $epFondoaRotatorio= $postData['magyp_rendiciondecajabundle_fondorotatoriotype'];

        $aRetencionTipos= $postData['retenciontipofactura'];
        $aeNumerosFactura= $postData['numerofactura'];
        $aeMontoFacturas= $postData['montofactura'];
        $aeImputaciones= $postData['imputacion'];
        $aeMontoImputaciones= $postData['montoimputacion'];
        
        $aidFondoFactura= $postData['idfondofactura'];
        $aidFondoImputacion= $postData['idifondoimputacion'];
        if ( isset ( $postData['borrar_factura'] ) ){
            $aBorrarFactura= $postData['borrar_factura'];
            if ( count($aBorrarFactura) == count($aeMontoFacturas) ){
                $cMensajeError= "No se pueden borrar todas las facturas.";
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
                $mensaje->setMensaje('Error', $cMensajeError)		    
                    ->Error()->NoExpira()
                    ->Generar();
                return $this->redirect($this->generateUrl('af_fondorotatorio_edit', array('id' => $id )));
            }
        }
        
        
        if ( isset ( $postData['borrar_imputacion'] ) ){
            $aBorrarImputacion= $postData['borrar_imputacion'];
            if ( count($aBorrarImputacion) == count($aeMontoImputaciones) ){
                $cMensajeError= "No se pueden borrar todas las imputaciones.";
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
                $mensaje->setMensaje('Error', $cMensajeError)		    
                    ->Error()->NoExpira()
                    ->Generar();
                return $this->redirect($this->generateUrl('af_fondorotatorio_edit', array('id' => $id )));
            }
        }
 
        
        
        /* @var $eFondoRotatorio FondoRotatorio */
        $eFondoRotatorio = $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorio')->find( $id );
        $eFondoRotatorio->setActividad($em->getRepository('MagypRendicionDeCajaBundle:Actividad')->find($epFondoaRotatorio['actividad']));
        $eFondoRotatorio->setArea($em->getRepository('MagypRendicionDeCajaBundle:Area')->find($epFondoaRotatorio['area']));
        $eFondoRotatorio->setBeneficiario($epFondoaRotatorio['beneficiario']);
        $eFondoRotatorio->setExpediente($epFondoaRotatorio['expediente']);
        //$fechaAux= new \DateTime();
        //$fechaAux->setDate($epFondoaRotatorio['fecha']['year'], $epFondoaRotatorio['fecha']['month'], $epFondoaRotatorio['fecha']['day']);
        $eFondoRotatorio->setMotivo($epFondoaRotatorio['motivo']);
        $eFondoRotatorio->setNota($epFondoaRotatorio['nota']);
        $eFondoRotatorio->setPrograma($em->getRepository('MagypRendicionDeCajaBundle:Programa')->find($epFondoaRotatorio['programa']));
        $eFondoRotatorio->setUg($em->getRepository('MagypRendicionDeCajaBundle:UbicacionGeografica')->find($epFondoaRotatorio['ug']));
        
        $userCompleto = $this->getDoctrine()->getManager()->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($this->getUsuario()->getId());
        $em->refresh($userCompleto);
        $eFondoRotatorio->setResponsable($userCompleto);
        
        $em->persist($eFondoRotatorio);
        $em->flush();
       
        //agrego las retencionestipo
        $aeFondoRotatorioFacturaRetencion= $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorioRetencion')->findBy( array ( 'fondorotatorio' => $id ));
        
        $nPosRetencionFondo= 0;
        foreach ( $aeFondoRotatorioFacturaRetencion as $eFondoRotatorioFacturaRetencion ){
            if ( isset ($aRetencionTipos[$nPosRetencionFondo])){
                $eRetencionTipo = $em->getRepository('MagypRendicionDeCajaBundle:RetencionTipo')->find( $aRetencionTipos[$nPosRetencionFondo] );
                $eFondoRotatorioFacturaRetencion->setRetenciontipo($eRetencionTipo);
                
                 switch ($eRetencionTipo->getId()){
                    case 1:
                        $eFondoRotatorioFacturaRetencion->setMonto($montoIva);
                    break;
                    case 2:
                        $eFondoRotatorioFacturaRetencion->setMonto($montoGanancia);
                    break;
                    case 3:
                        $eFondoRotatorioFacturaRetencion->setMonto($montoResolucion);
                    break;
                    default:
                        die ("tipo incorrecto de retencion");
                }
                
                
                $em->persist($eFondoRotatorioFacturaRetencion);
                //reseteo los modificados
            }else{
                $em->remove($eFondoRotatorioFacturaRetencion);
                //borro los q sobran
            }
            $em->flush();
            $nPosRetencionFondo++;
        }
        
        //aca guardo los nuevos
        while ( isset ( $aRetencionTipos[$nPosRetencionFondo] ) ){

            $eFondoRotatorioFacturaRetencion= new FondoRotatorioRetencion();
            $eRetencionTipo = $em->getRepository('MagypRendicionDeCajaBundle:RetencionTipo')->find( $aRetencionTipos[$nPosRetencionFondo] );
            $eFondoRotatorioFacturaRetencion->setRetenciontipo($eRetencionTipo);
            $eFondoRotatorioFacturaRetencion->setFondorotatorio($eFondoRotatorio);

            switch ($eRetencionTipo->getId()){
                case 1:
                    $eFondoRotatorioFacturaRetencion->setMonto($montoIva);
                break;
                case 2:
                    $eFondoRotatorioFacturaRetencion->setMonto($montoGanancia);
                break;
                case 3:
                    $eFondoRotatorioFacturaRetencion->setMonto($montoResolucion);
                break;
                default:
                    die ("tipo incorrecto de retencion");
            }
            $em->persist($eFondoRotatorioFacturaRetencion);

            $nPosRetencionFondo++;
        }
        $em->flush();
        
        //inserto los modificados
        for ( $nPosFactura= 0 ; $nPosFactura < count ($aidFondoFactura) ; $nPosFactura++){
            
            $eFondoRotatorioFactura= $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorioFactura')->find( $aidFondoFactura[$nPosFactura] );
            
            $eTipoFactura= $em->getRepository('MagypRendicionDeCajaBundle:TipoFactura')->find( $aTipofactura[$nPosFactura] );

            $eFondoRotatorioFactura->setNumero($aeNumerosFactura[$nPosFactura]);
            $eFondoRotatorioFactura->setMonto($aeMontoFacturas[$nPosFactura]);
            $eFondoRotatorioFactura->setTipoFactura($eTipoFactura);
            
            $em->persist($eFondoRotatorioFactura);
        }
        $em->flush();
        
        //inserto los nuevos
        for ( $nPosFactura= count ($aidFondoFactura) ; $nPosFactura < count ($aeNumerosFactura) ; $nPosFactura++){
            $eFondoRotatorioFactura= new FondoRotatorioFactura();
            $eTipoFactura= $em->getRepository('MagypRendicionDeCajaBundle:TipoFactura')->find( $aTipofactura[$nPosFactura] );
            $eFondoRotatorioFactura->setTipoFactura($eTipoFactura);
            $eFondoRotatorioFactura->setNumero($aeNumerosFactura[$nPosFactura]);
            $eFondoRotatorioFactura->setMonto($aeMontoFacturas[$nPosFactura]);
            $eFondoRotatorioFactura->setFondoRotatorio($eFondoRotatorio);
            $em->persist($eFondoRotatorioFactura);
        }
        $em->flush();
        //borro los seleccionados
        
        for ( $nPosBorrables= 0; $nPosBorrables < $nPosFactura; $nPosBorrables++ ){
            if ( isset ( $aBorrarFactura[$nPosBorrables] ) ){
                $eFondoRotatorioFacturaABorrar= $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorioFactura')->find( $aBorrarFactura[$nPosBorrables] );
                $em->remove($eFondoRotatorioFacturaABorrar);
                $em->flush();
            }
        }
        
        //modifico los exsitentes
        for ( $nPosImputacion= 0 ; $nPosImputacion < count ($aidFondoImputacion) ; $nPosImputacion++){
            $eFondoRotatorioImputacion= $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorioImputacion')->find( $aidFondoImputacion[$nPosImputacion] );

            $eImputacion = $em->getRepository('MagypRendicionDeCajaBundle:Imputacion')->find( $aeImputaciones[$nPosImputacion] );
            $eFondoRotatorioImputacion->setImputacion($eImputacion);
            $eFondoRotatorioImputacion->setMonto($aeMontoImputaciones[$nPosImputacion]);
            $em->persist($eFondoRotatorioImputacion);
        }
        $em->flush();
        
        //borro los seleccionados
        for ( $nPosBorrables= 0; $nPosBorrables < $nPosImputacion; $nPosBorrables++ ){
            if ( isset ( $aBorrarImputacion[$nPosBorrables] ) ){
                $eFondoRotatorioImputacionABorrar= $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorioImputacion')->find( $aBorrarImputacion[$nPosBorrables] );
                $em->remove($eFondoRotatorioImputacionABorrar);
                $em->flush();
            }
        }
        //agrego los nuevos
        for ( $nPosImputacion= count ($aidFondoImputacion) ; $nPosImputacion < count ($aeMontoImputaciones) ; $nPosImputacion++){
            $eFondoRotatorioImputacion= new FondoRotatorioImputacion();
            $eImputacion = $em->getRepository('MagypRendicionDeCajaBundle:Imputacion')->find( $aeImputaciones[$nPosImputacion] );
            $eFondoRotatorioImputacion->setImputacion($eImputacion);
            $eFondoRotatorioImputacion->setMonto($aeMontoImputaciones[$nPosImputacion]);

            $eFondoRotatorioImputacion->setFondoRotatorio($eFondoRotatorio);
            $em->persist($eFondoRotatorioImputacion);
        }
        $em->flush();
        $cMensajeError= "Correcto.";
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
                $mensaje->setMensaje('Exito', $cMensajeError)		    
                    ->Exito()
                    ->Generar();
        return $this->redirect($this->generateUrl('af_fondorotatorio_show', array('id' => $eFondoRotatorio->getId() )));
        
        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
