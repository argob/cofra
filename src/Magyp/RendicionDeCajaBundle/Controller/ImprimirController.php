<?php
namespace Magyp\RendicionDeCajaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\Rendicion;
use Magyp\RendicionDeCajaBundle\Form\RendicionType;

use Magyp\RendicionDeCajaBundle\Entity\Comprobante;
use Magyp\RendicionDeCajaBundle\Entity\ComprobanteRepository;
use Framework\GeneralBundle\Entity\NumerosAletras;
use Framework\GeneralBundle\Entity\CodigoDeBarras;

use Framework\GeneralBundle\Entity\Formateador;

/**
 * Imprimir controller.
 *
 * @Route("/imprimir")
 */
class ImprimirController extends Controller
{
    /**
     *
     * @Route("/generalpdf/{idrendicion}/{tipo}/{hash}", name="imprimir_general_pdf")
     */
    public function imprimirGeneralPdf($idrendicion, $tipo, $hash)
    {
        $em = $this->getDoctrine()->getManager();
        $rendicion = $em->getRepository("MagypRendicionDeCajaBundle:Rendicion")->find($idrendicion);
        $hashParaValidar= md5($rendicion->getId().$rendicion->getResponsable().$rendicion->getArea());
		
		if ($hashParaValidar == $hash) {

            switch($tipo){
                case 2:
                    $cTipo= "-Almuerzos";
                break;

                default:
                    $cTipo= "";

            } 

            $pageUrl = $this->generateUrl('imprimir_general', array( 'idrendicion' => $idrendicion, 'tipo'=> $tipo, 'hash'=> $hash ), true);
            return new \Symfony\Component\HttpFoundation\Response(
                $this->get('knp_snappy.pdf')->getOutput($pageUrl),
                200,
                array(
                    'Content-Type'          => 'application/pdf',
                    'Content-Disposition'   => "attachment; filename= Cofra$cTipo-$idrendicion.pdf"
                )
            );
        
        }else{
            $mensajeAEnviar= "Hash de rendicion invalida.";
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
            $mensaje->setMensaje('Error',$mensajeAEnviar)		    
            ->Error()->NoExpira()
            ->Generar();
            return $this->redirect($this->generateUrl('sistema_rendicion'));
        }
    }
    
    
    /**
     *
     * @Route("/general/{idrendicion}/{tipo}/{hash}", name="imprimir_general")
     * @Template("MagypRendicionDeCajaBundle:Impresion:general.html.twig")
     */
    public function imprimirGeneral($idrendicion, $tipo, $hash)
    {
        $em = $this->getDoctrine()->getManager();
        
        $rendicion = $em->getRepository("MagypRendicionDeCajaBundle:Rendicion")->find($idrendicion);

        $hashParaValidar= md5($rendicion->getId().$rendicion->getResponsable().$rendicion->getArea());
		
        if ($hashParaValidar == $hash) {
        
            if(is_null($rendicion)){
                die ('No se encontro la rendicion elegida.');
                return new \Symfony\Component\HttpFoundation\Response();
            }

            switch($tipo){
                case 1:
                    $comprobantes = $em->getRepository('MagypRendicionDeCajaBundle:Comprobante')->buscarActivosOrdenadosPorImputacion($idrendicion);
                break;
                case 2:
                    $comprobantes = $em->getRepository('MagypRendicionDeCajaBundle:Comprobante')->buscarActivosAlmuerzos($idrendicion);
                break;

                default:
                    die("tipo de impresion erronea");
            } 

            $area = $rendicion->getArea();

            $editForm = $this->createForm(new RendicionType(), $rendicion);
            $preimpu= "";
            $pos= 0;
            $total= 0;
            $comprobantesOrdenadosDetalles= array();
            $importeFormateado= new formateador();
            
            foreach ( $comprobantes as $comp ){
                if ( ( $preimpu == "" ) || ( $preimpu != $comp->getImputacion()->getCodigo()) ) {
                    if ( isset ($comprobantesSubtotales["$preimpu"])){
                        $comprobantesSubtotales["$preimpu"]= $importeFormateado->formateameNumero($comprobantesSubtotales["$preimpu"]);
                    }
                    $preimpu= $comp->getImputacion()->getCodigo();
                    $comprobantesSubtotales["$preimpu"]= 0;
                }

                $comprobantesOrdenadosDetalles["$preimpu"][$pos]= array (
                    'fecha'=> $comp->getFecha(), 
                    'numero'=> $comp->getNumero(), 
                    'imputacion'=> $comp->getImputacion()->getCodigo(),
                    'proveedor'=> $comp->getProveedor()->getDescripcion(),
                    'importe'=> $importeFormateado->formateameNumero($comp->getImporte()) );
                $comprobantesSubtotales["$preimpu"]= $comprobantesSubtotales["$preimpu"] + $comp->getImporte();
                $total= $total + $comp->getImporte();
                $pos++;
            }
            $comprobantesSubtotales["$preimpu"]= $importeFormateado->formateameNumero($comprobantesSubtotales["$preimpu"]);
            $codigoDeBarras= new codigodebarras();
            $cCodigoDeBarras= $codigoDeBarras->getCodigoDeBarras( $tipo, $idrendicion);
            
            $rendicion->setExpediente($rendicion->getExpedienteCompleto());
              
            $eLeyenda = $em->getRepository("MagypRendicionDeCajaBundle:Leyenda")->findOneBy(array ( "asignado" => true ));
            
            $cArea = $rendicion->getArea()->getNombre();
            
            $total= $importeFormateado->formateameNumero($total);
            
            return array(
                'comprobantesDetalles' => $comprobantesOrdenadosDetalles,
                'comprobantesSubtotales' => $comprobantesSubtotales,
                'total'          => $total,
                'rendicion'      => $rendicion,
                'area'           => $area,
                'edit_form'   => $editForm->createView(),
                'codigodebarras' => $cCodigoDeBarras,
                'leyenda'       => $eLeyenda,
                'areamembrete'  =>  $cArea,
            );
        }else{
            $mensajeAEnviar= "Hash de rendicion invalida.";
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
            $mensaje->setMensaje('Error',$mensajeAEnviar)		    
            ->Error()->NoExpira()
            ->Generar();
            return $this->redirect($this->generateUrl('sistema_rendicion'));
        }
    }
    
    
    /**
     * @Route("/liquidacionpdf/{idliquidacion}/{hash}", name="imprimir_liquidacion_pdf")
     */
    public function imprimirLiquidacionPdf($idliquidacion, $hash)
    {
        $em = $this->getDoctrine()->getManager();
        $liquidacion = $em->getRepository("MagypRendicionDeCajaBundle:Liquidacion")->find($idliquidacion);
        $hashParaValidar= md5($liquidacion->getId().$liquidacion->getResponsable().$liquidacion->getArea());
        
        if ( $hashParaValidar == $hash){
            $pageUrl = $this->generateUrl('imprimir_liquidacion', array( 'idliquidacion'=> $idliquidacion, 'hash'=> $hash ), true);
            return new \Symfony\Component\HttpFoundation\Response(
                $this->get('knp_snappy.pdf')->getOutput($pageUrl),
                200,
                array(
                    'Content-Type'          => 'application/pdf',
                    'Content-Disposition'   => "attachment; filename= CofraLiquidacion-$idliquidacion.pdf"
                )
            );
        }else{
            $mensajeAEnviar= "Hash de liquidacion invalida.";
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
            $mensaje->setMensaje('Error',$mensajeAEnviar)		    
            ->Error()->NoExpira()
            ->Generar();
            return $this->redirect($this->generateUrl('af_home'));
        }
       
    }
    
    
   	/**
     *
     * @Route("/liquidacion/{idliquidacion}/{hash}", name="imprimir_liquidacion")
     * @Template("MagypRendicionDeCajaBundle:Impresion:liquidacion.html.twig")
     */
    public function imprimirLiquidacion($idliquidacion, $hash)
    {
        $importeFormateado= new formateador();
        $em = $this->getDoctrine()->getManager();
        $liquidacion = $em->getRepository("MagypRendicionDeCajaBundle:Liquidacion")->find($idliquidacion);
        $hashParaValidar= md5($liquidacion->getId().$liquidacion->getResponsable().$liquidacion->getArea());

        if ( $hashParaValidar == $hash){
            
            $liquidacion->setUg(str_pad($liquidacion->getUg(), 2, "0", STR_PAD_LEFT));
            $liquidacion->setActividad(str_pad($liquidacion->getActividad(), 2, "0", STR_PAD_LEFT));
            $liquidacion->setFuenteFinanciamiento(str_pad($liquidacion->getFuenteFinanciamiento(), 2, "0", STR_PAD_LEFT));

            $detalles = $em->getRepository("MagypRendicionDeCajaBundle:LiquidacionDetalle")->buscarDetallesDeLiquidacion( $idliquidacion );
            $nTotal= 0;
            foreach ($detalles as $detalle ){
                $detalle->setPrograma(str_pad($detalle->getPrograma(), 2, "0", STR_PAD_LEFT));
                $nTotal= $nTotal + $detalle->getImportesubtotal();
                $detalle->setImportesubtotal($importeFormateado->formateameNumero($detalle->getImportesubtotal()));
            }
            
            $codigoDeBarras= new codigodebarras();
            $reporteTipo= 5;
            $cCodigoDeBarras= $codigoDeBarras->getCodigoDeBarras( $reporteTipo, $idliquidacion);
            
            $rendicion= $liquidacion->getRendicion();
            $liquidacion->setExpediente($rendicion->getExpedienteCompleto());
            
            $eLeyenda = $em->getRepository("MagypRendicionDeCajaBundle:Leyenda")->findOneBy(array ( "asignado" => true ));
            
            $eAreaMemebrete = $em->getRepository("MagypRendicionDeCajaBundle:Area")->find( 1000 );
            $cArea = $eAreaMemebrete->getNombre();
            
            $nTotal= $importeFormateado->formateameNumero($nTotal);
            
            return array(
                'liquidacion' => $liquidacion,
                'detalles' => $detalles,
                'total' => $nTotal,
                'codigodebarras' => $cCodigoDeBarras,
                'leyenda' => $eLeyenda,
                'areamembrete' => $cArea,
            );
       }else{
            $mensajeAEnviar= "Hash de liquidacion invalida.";
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
            $mensaje->setMensaje('Error',$mensajeAEnviar)		    
            ->Error()->NoExpira()
            ->Generar();
            return $this->redirect($this->generateUrl('af_home'));
        }
    }
    
    
    /**
     *
     * @Route("/notadecomidapdf/{idrendicion}/{hash}", name="imprimir_notadecomida_pdf")
     */
    public function imprimirNotaDeComidaPdf($idrendicion, $hash)
    {
        $em = $this->getDoctrine()->getManager();
        $eRendicion = $em->getRepository("MagypRendicionDeCajaBundle:Rendicion")->find($idrendicion);
        $hashParaValidar= md5($eRendicion->getId().$eRendicion->getResponsable().$eRendicion->getArea());
        if ( $hashParaValidar == $hash ){

            $eComprobantes = $em->getRepository('MagypRendicionDeCajaBundle:Comprobante')->buscarActivosAlmuerzos($idrendicion);

            if ( !$eComprobantes ){
                $mensajeAEnviar= "La rendicion no posee comprobantes del tipo Almuerzo para imprimir";
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                $mensaje->setMensaje('Error',$mensajeAEnviar)		    
                ->Error()->NoExpira()
                ->Generar();
                return $this->redirect($this->generateUrl('sistema_rendicion_detalle', array('idrendicion' => $idrendicion)));
            }



            $pageUrl = $this->generateUrl('imprimir_notadecomida', array( 'idrendicion'=> $idrendicion, 'hash'=> $hash ), true);
            return new \Symfony\Component\HttpFoundation\Response(
                $this->get('knp_snappy.pdf')->getOutput($pageUrl),
                200,
                array(
                    'Content-Type'          => 'application/pdf',
                    'Content-Disposition'   => "attachment; filename= CofraNotaDeComida-$idrendicion.pdf"
                )
            );

        }else{
            $mensajeAEnviar= "Hash de liquidacion invalida.";
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
            $mensaje->setMensaje('Error',$mensajeAEnviar)		    
            ->Error()->NoExpira()
            ->Generar();
            return $this->redirect($this->generateUrl('af_home'));
        }
        
    }
    

    /**
     *
     * @Route("/notadecomida/{idrendicion}/{hash}", name="imprimir_notadecomida")
     * @Template("MagypRendicionDeCajaBundle:Impresion:notadecomida.html.twig")
     */
    public function imprimirNotaDeComida($idrendicion, $hash)
    {
        $importeFormateado= new formateador();
        $em = $this->getDoctrine()->getManager();
        $eRendicion = $em->getRepository("MagypRendicionDeCajaBundle:Rendicion")->find($idrendicion);
        $hashParaValidar= md5($eRendicion->getId().$eRendicion->getResponsable().$eRendicion->getArea());
        if ( $hashParaValidar == $hash ){
            $eRendicion = $em->getRepository("MagypRendicionDeCajaBundle:Rendicion")->find($idrendicion);

            $eComprobantes = $em->getRepository('MagypRendicionDeCajaBundle:Comprobante')->buscarActivosAlmuerzos($idrendicion);

            $codigoDeBarras= new codigodebarras();
            $reporteTipo= 3;
            $cCodigoDeBarras= $codigoDeBarras->getCodigoDeBarras( $reporteTipo, $idrendicion);
            
            $eLeyenda = $em->getRepository("MagypRendicionDeCajaBundle:Leyenda")->findOneBy(array ( "asignado" => true ));
            
            $cArea = $eRendicion->getArea()->getNombre();
            
            foreach ($eComprobantes as $eComprobante ){
                $eComprobante->setImporte($importeFormateado->formateameNumero($eComprobante->getImporte()) );
            }
            
            return array(
                'fecharendicion' => $importeFormateado->formateameFecha($eRendicion->getFecha()),
                'rendicion' => $eRendicion,
                'comprobantes' => $eComprobantes,
                'codigodebarras' => $cCodigoDeBarras,
                'leyenda' => $eLeyenda,
                'areamembrete' => $cArea,
           );
        }else{
            $mensajeAEnviar= "Hash de liquidacion invalida.";
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
            $mensaje->setMensaje('Error',$mensajeAEnviar)		    
            ->Error()->NoExpira()
            ->Generar();
            return $this->redirect($this->generateUrl('af_home'));
        }

        
    }
    
    
    /**
     * @Route("/rendiciondecajachicapdf/{idrendicion}/{hash}", name="imprimir_rendiciondecajachica_pdf")
     */
    public function rendicionDeCajaChicaPdf($idrendicion, $hash)
    {
        $em = $this->getDoctrine()->getManager();
        $eRendicion = $em->getRepository("MagypRendicionDeCajaBundle:Rendicion")->find($idrendicion);
        $hashParaValidar= md5($eRendicion->getId().$eRendicion->getResponsable().$eRendicion->getArea());
        
        $eComprobantes = $em->getRepository("MagypRendicionDeCajaBundle:Comprobante")->buscarActivosOrdenadosPorImputacionConSubTotal( $idrendicion );
 
        if (count ($eComprobantes) == 0){
            $mensajeAEnviar= "No tiene comprobantes para imprimir.";
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
            $mensaje->setMensaje('Error',$mensajeAEnviar)		    
            ->Error()->NoExpira()
            ->Generar();
            return $this->redirect($this->generateUrl('sistema_rendicion_detalle', array ( 'idrendicion' => $idrendicion )));
        }
        
        if ( $hashParaValidar == $hash ){
            $pageUrl = $this->generateUrl('imprimir_rendiciondecajachica', array( 'idrendicion'=> $idrendicion, 'hash'=> $hash ), true);
            return new \Symfony\Component\HttpFoundation\Response(
                $this->get('knp_snappy.pdf')->getOutput($pageUrl),
                200,
                array(
                    'Content-Type'          => 'application/pdf',
                    'Content-Disposition'   => "attachment; filename= CofraRendicionDeCajaChica-$idrendicion.pdf"
                )
            );

        }else{
            $mensajeAEnviar= "Hash de rendicion de caja chica invalida.";
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
            $mensaje->setMensaje('Error',$mensajeAEnviar)		    
            ->Error()->NoExpira()
            ->Generar();
            return $this->redirect($this->generateUrl('sistema_rendicion'));
        }
    }
    
    /**
     *
     * @Route("/rendiciondecajachica/{idrendicion}/{hash}", name="imprimir_rendiciondecajachica")
     * @Template("MagypRendicionDeCajaBundle:Impresion:rendiciondecajachica.html.twig")
     */
    public function rendicionDeCajaChica($idrendicion, $hash)
    {
        $importeFormateado= new formateador();
        $em = $this->getDoctrine()->getManager();
        $eRendicion = $em->getRepository("MagypRendicionDeCajaBundle:Rendicion")->find($idrendicion);

        $nTotal= $this->getDoctrine()->getRepository("MagypRendicionDeCajaBundle:Comprobante")->sumatoriaActivos($idrendicion);
        $nTotal= $nTotal[0][1];

        $hashParaValidar= md5($eRendicion->getId().$eRendicion->getResponsable().$eRendicion->getArea());
        
        if ( $hashParaValidar == $hash ){
        
            $numerosaletras= new numerosaletras();

            $codigoDeBarras= new codigodebarras();
            $reporteTipo= 3;
            $cCodigoDeBarras= $codigoDeBarras->getCodigoDeBarras( $reporteTipo, $idrendicion);

            $eLeyenda = $em->getRepository("MagypRendicionDeCajaBundle:Leyenda")->findOneBy(array ( "asignado" => true ));
            
            $cArea = $eRendicion->getArea()->getNombre();
            
            $importeFormateado= new formateador();
            
            return array(
                'fecharendicion' => $importeFormateado->formateameFecha($eRendicion->getFecha()),
                'rendicion' => $eRendicion,
                'montoaletras' => $numerosaletras->convertir_a_letras($nTotal),
                'total' => $importeFormateado->formateameNumero($nTotal),
                'codigodebarras' => $cCodigoDeBarras,
                'expediente' => $eRendicion->getExpedienteCompletoCorto(),
                'leyenda' => $eLeyenda,
                'areamembrete' => $cArea,
            );
        }else{
            $mensajeAEnviar= "Hash de rendicion de caja chica invalida.";
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
            $mensaje->setMensaje('Error',$mensajeAEnviar)		    
            ->Error()->NoExpira()
            ->Generar();
            return $this->redirect($this->generateUrl('sistema_rendicion'));
        }

    }
    
  
    /**
     *
     * @Route("/anticipodegastospdf/{idanticipodegastos}/{hash}", name="imprimir_anticipodegastos_pdf")
     * @Template("MagypRendicionDeCajaBundle:Impresion:anticipodegastos.html.twig")
     */
    public function anticipoDeGastosDeCajaChicaPdf($idanticipodegastos, $hash)
    {
        $em = $this->getDoctrine()->getManager();
        $eAnticipoDeGasto = $em->getRepository("MagypRendicionDeCajaBundle:AnticipoDeGasto")->find($idanticipodegastos);
        $hashParaValidar= md5($eAnticipoDeGasto->getId().$eAnticipoDeGasto->getExpediente().$eAnticipoDeGasto->getArea());
        
        if ( $hashParaValidar == $hash ){
            $pageUrl = $this->generateUrl('imprimir_anticipodegastos', array( 'idanticipodegastos'=> $idanticipodegastos, 'hash'=> $hash ), true);
            return new \Symfony\Component\HttpFoundation\Response(
                $this->get('knp_snappy.pdf')->getOutput($pageUrl),
                200,
                array(
                    'Content-Type'          => 'application/pdf',
                    'Content-Disposition'   => "attachment; filename= CofraAnticipoDeGastos-$idanticipodegastos.pdf"
                )
            );
        }else{
            $mensajeAEnviar= "Hash de Anticipo De Gastos invalida.";
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
            $mensaje->setMensaje('Error',$mensajeAEnviar)		    
            ->Error()->NoExpira()
            ->Generar();
            return $this->redirect($this->generateUrl('af_home'));
        }
    }
    
    
     /**
     *
     * @Route("/anticipodegastos/{idanticipodegastos}/{hash}", name="imprimir_anticipodegastos")
     * @Template("MagypRendicionDeCajaBundle:Impresion:anticipodegastos.html.twig")
     */
    public function anticipoDeGastosDeCajaChica($idanticipodegastos, $hash)
    {
        $importeFormateado= new formateador();
        $numerosaletras= new numerosaletras();
        $em = $this->getDoctrine()->getManager();
        
        $eAnticipoDeGastos = $em->getRepository("MagypRendicionDeCajaBundle:AnticipoDeGasto")->find($idanticipodegastos);
        
        $hashParaValidar= md5($eAnticipoDeGastos->getId().$eAnticipoDeGastos->getExpediente().$eAnticipoDeGastos->getArea());
        
        if ( $hashParaValidar == $hash ){
        
            $iniciales= substr( $eAnticipoDeGastos->getResponsable()->getNombre(), 0, 1).substr( $eAnticipoDeGastos->getResponsable()->getApellido(), 0, 1);
            
            $codigoDeBarras= new codigodebarras();
            $reporteTipo= 6;
            $cCodigoDeBarras= $codigoDeBarras->getCodigoDeBarras( $reporteTipo, $idanticipodegastos);
            
            $eLeyenda = $em->getRepository("MagypRendicionDeCajaBundle:Leyenda")->findOneBy(array ( "asignado" => true ));
            
            $eAreaMemebrete = $em->getRepository("MagypRendicionDeCajaBundle:Area")->find( 1000 );
            $cArea = $eAreaMemebrete->getNombre();
            
            $cNumerosALetras= $numerosaletras->convertir_a_letras($eAnticipoDeGastos->getMonto());
            $eAnticipoDeGastos->setMonto($importeFormateado->formateameNumero($eAnticipoDeGastos->getMonto()));
            
            return array(
                'anticipodegastos' => $eAnticipoDeGastos,
                'montoaletras' => $cNumerosALetras,
                'iniciales' => $iniciales,
                'codigodebarras' => $cCodigoDeBarras,
                'leyenda'   => $eLeyenda,
                'areamembrete' => $cArea,
            );
        }else{
            $mensajeAEnviar= "Hash de Anticipo De Gastos invalida.";
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
            $mensaje->setMensaje('Error',$mensajeAEnviar)		    
            ->Error()->NoExpira()
            ->Generar();
            return $this->redirect($this->generateUrl('af_home'));
        }
    }
    
    
    /**
     * @Route("/fondorotatoriopdf/{idfondorotatorio}/{hash}", name="imprimir_fondorotatorio_pdf")
     */
    public function fondoRotatorioPdf($idfondorotatorio, $hash)
    {
        $em = $this->getDoctrine()->getManager();
        $eFondoRotatorio = $em->getRepository("MagypRendicionDeCajaBundle:FondoRotatorio")->find($idfondorotatorio);
        $hashParaValidar= md5($eFondoRotatorio->getId().$eFondoRotatorio->getExpediente().$eFondoRotatorio->getArea());
        
        if ( $hashParaValidar == $hash ){
            $pageUrl = $this->generateUrl('imprimir_fondorotatorio', array( 'idfondorotatorio'=> $idfondorotatorio, 'hash'=> $hash ), true);
            return new \Symfony\Component\HttpFoundation\Response(
                $this->get('knp_snappy.pdf')->getOutput($pageUrl),
                200,
                array(
                    'Content-Type'          => 'application/pdf',
                    'Content-Disposition'   => "attachment; filename= CofraFondoRotatorio-$idfondorotatorio.pdf"
                )
            );
        }else{
            $mensajeAEnviar= "Hash de Anticipo De Gastos invalida.";
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
            $mensaje->setMensaje('Error',$mensajeAEnviar)		    
            ->Error()->NoExpira()
            ->Generar();
            return $this->redirect($this->generateUrl('af_home'));
        }
    }
    
    
    /**
     *
     * @Route("/fondorotatorio/{idfondorotatorio}/{hash}", name="imprimir_fondorotatorio")
     * @Template("MagypRendicionDeCajaBundle:Impresion:fondorotatorio.html.twig")
     */
    public function fondoRotatorio($idfondorotatorio, $hash)
    {
        $importeFormateado= new formateador();
        $em = $this->getDoctrine()->getManager();

        $eFondoRotatorio= $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorio')->find($idfondorotatorio);

        $hashParaValidar= md5($eFondoRotatorio->getId().$eFondoRotatorio->getExpediente().$eFondoRotatorio->getArea());
        
        if ( $hashParaValidar == $hash ){
        
            $aeFondoRotatorioFactura= $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorioFactura')->findBy(array ('fondorotatorio' => $idfondorotatorio ) );
            $aeFondoRotatorioImputacion= $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorioImputacion')->findBy(array ('fondorotatorio' => $idfondorotatorio ) );
            $aeFondoRotatorioFacturaRetencion= $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorioRetencion')->findBy(array ('fondorotatorio' => $idfondorotatorio ) );

            $eRetencionTipo = $em->getRepository("MagypRendicionDeCajaBundle:RetencionTipo")->findAll();
            $eImputaciones = $em->getRepository("MagypRendicionDeCajaBundle:Imputacion")->buscarImputaciones();
            $aeTipoFactura= $em->getRepository("MagypRendicionDeCajaBundle:TipoFactura")->findAll();
 
            $codigoDeBarras= new codigodebarras();
            $reporteTipo= 7;
            $cCodigoDeBarras= $codigoDeBarras->getCodigoDeBarras( $reporteTipo, $idfondorotatorio);

            $fTotal= 0;
            foreach ($aeFondoRotatorioFactura as $eFondoRotatorioFactura){
                $fTotal= $fTotal + $eFondoRotatorioFactura->getMonto();
                $eFondoRotatorioFactura->setMonto($importeFormateado->formateameNumero($eFondoRotatorioFactura->getMonto()));
            }
            
            
            $eFondoRotatorio->setPrograma(str_pad($eFondoRotatorio->getPrograma(), 2, "0", STR_PAD_LEFT));
            $eFondoRotatorio->setActividad(str_pad($eFondoRotatorio->getActividad(), 2, "0", STR_PAD_LEFT));
            $eFondoRotatorio->setUg(str_pad($eFondoRotatorio->getUg(), 2, "0", STR_PAD_LEFT));
            
            $eLeyenda = $em->getRepository("MagypRendicionDeCajaBundle:Leyenda")->findOneBy(array ( "asignado" => true ));
            
            $eAreaMemebrete = $em->getRepository("MagypRendicionDeCajaBundle:Area")->find( 1000 );
            $cArea = $eAreaMemebrete->getNombre();
            
            foreach ($aeFondoRotatorioImputacion as $eFondoRotatorioImputacion){
                $eFondoRotatorioImputacion->setMonto($importeFormateado->formateameNumero($eFondoRotatorioImputacion->getMonto()));  
            }
            
            foreach ($aeFondoRotatorioFacturaRetencion as $eFondoRotatorioFacturaRetencion){
                $fTotal= $fTotal - $eFondoRotatorioFacturaRetencion->getMonto(); 
                $eFondoRotatorioFacturaRetencion->setMonto($importeFormateado->formateameNumero($eFondoRotatorioFacturaRetencion->getMonto()));
            }
 
            return array(
                'fondorotatorio'      => $eFondoRotatorio,
                'fondorotatoriofacturas'     => $aeFondoRotatorioFactura,
                'fondorotatorioimputaciones'  => $aeFondoRotatorioImputacion,
                'fondorotatorioretenciones' => $aeFondoRotatorioFacturaRetencion,
                'retenciontipos' => $eRetencionTipo,
                'imputaciones' => $eImputaciones,
                'tipofacturas' => $aeTipoFactura,
                'codigodebarras' => $cCodigoDeBarras,
                'total' => $importeFormateado->formateameNumero($fTotal),
                'leyenda' => $eLeyenda,
                'areamembrete' => $cArea,
            );
        }else{
            $mensajeAEnviar= "Hash de Fondo Rotatorio invalida.";
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
            $mensaje->setMensaje('Error',$mensajeAEnviar)		    
            ->Error()->NoExpira()
            ->Generar();
            return $this->redirect($this->generateUrl('af_home'));
        }
    }
    
    /**
     * @Route("/reintegrodegastopdf/{idreintegrodegasto}/{hash}", name="imprimir_reintegrodegasto_pdf")
     */
    public function reintegroDeGastoPdf($idreintegrodegasto, $hash)
    {
        $em = $this->getDoctrine()->getManager();
        $eReintegroDeGasto = $em->getRepository('MagypRendicionDeCajaBundle:ReintegroDeGasto')->find($idreintegrodegasto);
        $hashParaValidar= md5($eReintegroDeGasto->getId().$eReintegroDeGasto->getExpediente().$eReintegroDeGasto->getArea());
        
        if ( $hashParaValidar == $hash ){
            $pageUrl = $this->generateUrl('imprimir_reintegrodegasto', array( 'idreintegrodegasto'=> $idreintegrodegasto, 'hash'=> $hash ), true);
            return new \Symfony\Component\HttpFoundation\Response(
                $this->get('knp_snappy.pdf')->getOutput($pageUrl),
                200,
                array(
                    'Content-Type'          => 'application/pdf',
                    'Content-Disposition'   => "attachment; filename= CofraReintegroDeGasto-$idreintegrodegasto.pdf"
                )
            );
        }else{
            $mensajeAEnviar= "Hash de Reintegro De Gasto invalida.";
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
            $mensaje->setMensaje('Error',$mensajeAEnviar)		    
            ->Error()->NoExpira()
            ->Generar();
            return $this->redirect($this->generateUrl('af_home'));
        }
    }
    
    
    /**
     *
     * @Route("/reintegrodegasto/{idreintegrodegasto}/{hash}", name="imprimir_reintegrodegasto")
     * @Template("MagypRendicionDeCajaBundle:Impresion:reintegrodegasto.html.twig")
     */
    public function reintegroDeGasto($idreintegrodegasto, $hash)
    {
        $importeFormateado= new formateador();
        $em = $this->getDoctrine()->getManager();
        $eReintegroDeGasto = $em->getRepository('MagypRendicionDeCajaBundle:ReintegroDeGasto')->find($idreintegrodegasto);
        $hashParaValidar= md5($eReintegroDeGasto->getId().$eReintegroDeGasto->getExpediente().$eReintegroDeGasto->getArea());
        $nTotal= 0;
        if ( $hashParaValidar == $hash ){
            
            $eReintegroDeGasto->setUg(str_pad($eReintegroDeGasto->getUg(), 2, "0", STR_PAD_LEFT));
            $eReintegroDeGasto->setActividad(str_pad($eReintegroDeGasto->getActividad(), 2, "0", STR_PAD_LEFT));
            $eReintegroDeGasto->setFuenteFinanciamiento(str_pad($eReintegroDeGasto->getFuenteFinanciamiento(), 2, "0", STR_PAD_LEFT));

            $detalles = $em->getRepository("MagypRendicionDeCajaBundle:ReintegroDeGastoDetalle")->findBy( array ( 'reintegrodegasto' => $idreintegrodegasto ) );
            
            foreach ($detalles as $detalle ){
                $detalle->setPrograma(str_pad($detalle->getPrograma(), 2, "0", STR_PAD_LEFT));
                $nTotal= $nTotal + $detalle->getImporteSubTotal();
                $detalle->setImporteSubTotal($importeFormateado->formateameNumero($detalle->getImporteSubTotal()));
            }
            
            $codigoDeBarras= new codigodebarras();
            $reporteTipo= 8;
            $cCodigoDeBarras= $codigoDeBarras->getCodigoDeBarras( $reporteTipo, $idreintegrodegasto);

            $eLeyenda = $em->getRepository("MagypRendicionDeCajaBundle:Leyenda")->findOneBy(array ( "asignado" => true ));
            
            $eAreaMemebrete = $em->getRepository("MagypRendicionDeCajaBundle:Area")->find( 1000 );
            $cArea = $eAreaMemebrete->getNombre();
            
            
            return array(
                'reintegrodegasto' => $eReintegroDeGasto,
                'detalles' => $detalles,
                'total' => $importeFormateado->formateameNumero($nTotal),
                'codigodebarras' => $cCodigoDeBarras,
                'leyenda' => $eLeyenda,
                'areamembrete' => $cArea,
            );
        }else{ 
            $mensajeAEnviar= "Hash de Reintegro De Gasto invalida.";
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
            $mensaje->setMensaje('Error',$mensajeAEnviar)		    
            ->Error()->NoExpira()
            ->Generar();
            return $this->redirect($this->generateUrl('af_home'));
        }
    }
    
}
				
