<?php

namespace Magyp\RendicionDeCajaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Request;
use Magyp\RendicionDeCajaBundle\Entity\Estado;
use Magyp\RendicionDeCajaBundle\Entity\Rendicion;
use Magyp\RendicionDeCajaBundle\Form\RendicionType;
use Framework\GeneralBundle\Entity\CodigoDeBarras;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Tesoreria controller.
 *
 * @Route("/sistema/tesoreria")
 */
class TesoreriaController extends BaseCofraController {

    /**
     * @Route("/", name="te_home")
     * @Secure(roles="ROLE_TESORERIA")
     * @Template("MagypRendicionDeCajaBundle:Tesoreria:tehome.html.twig")
     */
    public function tesoreriaAction() {
        return array();
    }

    /**
     * @Route("/listados", name="te_home_listados")
     * @Secure(roles="ROLE_TESORERIA")
     * @Template("MagypRendicionDeCajaBundle:Tesoreria:telistados.html.twig")
     */
    public function listadosAction() {
        return array();
    }

    /**
     * @Route("/rendiciones/atesoreria", name="te_rendiciones_atesoreria")
     * @Secure(roles="ROLE_TESORERIA")
     * @Template()
     */
    public function atesoreriaAction() {
        $em = $this->getDoctrine()->getManager();
        $rendiciones = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->conEstadoAtesoreria();
        //$areas= $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->getAreasConExpedientesEnviados();
        $qblista = $em->getRepository('MagypRendicionDeCajaBundle:Area')->qb_AreasConExpedientesAtesoreria();

        $paginador = $this->get('knp_paginator');
        $cantidad = $this->get('request')->query->get('cantidad');
        $cantidad = intval($cantidad) > 0 ? intval($cantidad) : 10;

        $lista = $paginador->paginate(
                $qblista->getQuery(), $this->get('request')->query->get('page', 1), $cantidad
        );
        return array('rendiciones' => $rendiciones, 'lista' => $lista);
    }

    /**
     * @Route("/rendiciones/aapagar", name="te_rendiciones_aapagar")
     * @Secure(roles="ROLE_TESORERIA")
     * @Template()
     */
    public function aapagarAction() {
        $em = $this->getDoctrine()->getManager();
        $rendiciones = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->conEstadoAtesoreria();
        //$areas= $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->getAreasConExpedientesEnviados();
        $qblista = $em->getRepository('MagypRendicionDeCajaBundle:Area')->qb_AreasConExpedientesAapagar();

        $paginador = $this->get('knp_paginator');
        $cantidad = $this->get('request')->query->get('cantidad');
        $cantidad = intval($cantidad) > 0 ? intval($cantidad) : 10;

        $lista = $paginador->paginate(
                $qblista->getQuery(), $this->get('request')->query->get('page', 1), $cantidad
        );
        return array('rendiciones' => $rendiciones, 'lista' => $lista);
    }

    /**
     * @Route("/rendiciones/apagado", name="te_rendiciones_apagado")
     * @Secure(roles="ROLE_TESORERIA")
     * @Template()
     */
    public function apagadoAction() {
        $em = $this->getDoctrine()->getManager();
        $rendiciones = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->conEstadoApagado();
        //$areas= $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->getAreasConExpedientesEnviados();
        $qblista = $em->getRepository('MagypRendicionDeCajaBundle:Area')->qb_AreasConExpedientesApagado();

        $paginador = $this->get('knp_paginator');
        $cantidad = $this->get('request')->query->get('cantidad');
        $cantidad = intval($cantidad) > 0 ? intval($cantidad) : 10;

        $lista = $paginador->paginate(
                $qblista->getQuery(), $this->get('request')->query->get('page', 1), $cantidad
        );
        return array('rendiciones' => $rendiciones, 'lista' => $lista);
    }

    /**
     * @Route("/rendiciones/lector", name="te_pistolear_home")
     * @Secure(roles="ROLE_TESORERIA")
     * @Template()
     */
    public function pistolearTesoreriaAction() {
        return array();
    }

    /**
     * Lists all Rendicion entities.
     *
     * @Secure(roles="ROLE_TESORERIA")
     * @Route("/rendiciones/detalle/{idrendicion}", name="te_rendicion_detalle")
     * @Template("MagypRendicionDeCajaBundle:Tesoreria:detalle.html.twig")
     */
    public function detalleRendicionAction($idrendicion) {
        $elusuarioesAF = $this->container->get('security.context')->isGranted('ROLE_AF');

        $controller = $this;

        $em = $controller->getDoctrine()->getManager();

        $rendicion = new Rendicion();
        $rendicion = $em->getRepository('MagypRendicionDeCajaBundle:Rendicion')->find($idrendicion);
        if (is_null($rendicion)) {
            echo 'No se encontro la rendicion elegida.';
            return new \Symfony\Component\HttpFoundation\Response();
        }
        $comprobantes = $em->getRepository('MagypRendicionDeCajaBundle:Comprobante')->buscarActivos($idrendicion);
        $usuario = $controller->getUsuario();
        /*if ($rendicion->getArea() !== $usuario->getArea() && $elusuarioesAF == false) {
            return new \Symfony\Component\HttpFoundation\Response('NO puede acceder a rendiciones que no pertenecen a su area.');
            //return $controller->redirect($controller->generateUrl('sistema_rendicion'));
        }*/
        $total = 0;
        $totaltipo2 = 0;
        $totaltipo3 = 0;
        $totaltipo4 = 0;
        foreach ($comprobantes as $comprobante) {
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

        $hashParaEnviar = md5($eRendicion->getId() . $eRendicion->getResponsable() . $eRendicion->getArea());

        $eArchivar = $em->getRepository("MagypRendicionDeCajaBundle:Archivar")->findOneBy(array('rendicion' => $eRendicion));

        return array(
            'comprobantes' => $comprobantes, 'total' => $total, 'idrendicion' => $idrendicion,
            'totalTipo2' => $totaltipo2,
            'totalTipo3' => $totaltipo3,
            'totalTipo4' => $totaltipo4,
            'rendicion' => $rendicion,
            'edit_form' => $editForm->createView(),
            'archivar' => $eArchivar,
            'hash' => $hashParaEnviar,
        );
    }

    /**
     * @Route("/pagar/{idrendicion}", name="te_pagar_new")
     * @Secure(roles="ROLE_TESORERIA")
     * @Template("MagypRendicionDeCajaBundle:Tesoreria:pagar.html.twig")
     */
    public function rendicionPagarCrearAction($idrendicion) {
        return array('idrendicion' => $idrendicion);
    }

    /**
     * @Route("/apagar/{idrendicion}", name="te_apagar_create")
     * @Secure(roles="ROLE_TESORERIA")
     * @Template("MagypRendicionDeCajaBundle:Tesoreria:pagar.html.twig")
     */
    public function rendicionArchivarAction(Request $request, $idrendicion) {
        $liquidacionenviada = $request->request->get('liquidacionenviada');

        $em = $this->getDoctrine()->getManager();

        $codigoDeBarras = new codigodebarras();
        $bCodigoDeBarrasValido = $codigoDeBarras->esValidoCodigoDeBarras($liquidacionenviada);
        if ($bCodigoDeBarrasValido) {
            $reporteTipo = substr($liquidacionenviada, 0, 2);
            $nCantDigitos = substr($liquidacionenviada, 2, 1);
            $idLiquidacion = substr($liquidacionenviada, 3, $nCantDigitos);
        } else {
            $mensajeAEnviar = "El codigo de barras invalido.";
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
            $mensaje->setMensaje('Error', $mensajeAEnviar)
                    ->Error()->NoExpira()
                    ->Generar();
            return $this->redirect($this->generateUrl('te_pagar_new', array('idrendicion' => $idrendicion)));
        }

        if ($reporteTipo != 5) {
            $mensajeAEnviar = "La impresion no es una liquidacion.";
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
            $mensaje->setMensaje('Error', $mensajeAEnviar)
                    ->Error()->NoExpira()
                    ->Generar();
            return $this->redirect($this->generateUrl('te_pagar_new', array('idrendicion' => $idrendicion)));
        }

        $eLiquidacion = $em->getRepository("MagypRendicionDeCajaBundle:Liquidacion")->find($idLiquidacion);

        if ($eLiquidacion->getRendicion()->getId() == $idrendicion) {

            if ($eLiquidacion->getRendicion()->isAtesoreria()) {

                $eArchivarLiquidacion = $em->getRepository("MagypRendicionDeCajaBundle:Archivar")->findOneBy(array('liquidacion' => $eLiquidacion));
                if (empty($eArchivarLiquidacion)) {
                    $eRendicion = $em->getRepository("MagypRendicionDeCajaBundle:Rendicion")->find($idrendicion);
                    $eArchivarRendicion = $em->getRepository("MagypRendicionDeCajaBundle:Archivar")->findOneBy(array('rendicion' => $eRendicion));
                    if (empty($eArchivarRendicion)) {
                        return $this->redirect($this->generateUrl('sistema_estado_rendicion_apagar', array('idrendicion' => $idrendicion, 'idliquidacion' => $idLiquidacion)));
                    } else {
                        $mensajeAEnviar = "La rendicion ya se encuentra en estado A PAGAR.";
                        $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                        $mensaje->setMensaje('Error', $mensajeAEnviar)
                                ->Error()->NoExpira()
                                ->Generar();
                        return $this->redirect($this->generateUrl('te_pagar_new', array('idrendicion' => $idrendicion)));
                    }
                } else {
                    $mensajeAEnviar = "El codigo de barras de la liquidacion ingresada ya se encuentra en estado A PAGAR.";
                    $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                    $mensaje->setMensaje('Error', $mensajeAEnviar)
                            ->Error()->NoExpira()
                            ->Generar();
                    return $this->redirect($this->generateUrl('te_pagar_new', array('idrendicion' => $idrendicion)));
                }
            } else {
                $mensajeAEnviar = "La liquidacion no esta en estado A TESORERIA.";
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                $mensaje->setMensaje('Error', $mensajeAEnviar)
                        ->Error()->NoExpira()
                        ->Generar();
                return $this->redirect($this->generateUrl('te_pagar_new', array('idrendicion' => $idrendicion)));
            }
        } else {
            $mensajeAEnviar = "El codigo de barras de la liquidacion no se corresponde con la rendicion elegida.";
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
            $mensaje->setMensaje('Error', $mensajeAEnviar)
                    ->Error()->NoExpira()
                    ->Generar();
            return $this->redirect($this->generateUrl('te_pagar_new', array('idrendicion' => $idrendicion)));
        }

        return $this->redirect($this->generateUrl('te_rendiciones_atesoreria'));
    }

    /**
     * @Route("/buscar", name="te_buscador_por_liquidacion")
     * @Secure(roles="ROLE_TESORERIA")
     * @Template()
     */
    public function buscadorPorLiquidacionAction(Request $request) {
        $codigoPorPost = $request->request->get('codigodebarras');
        $codigoDeBarras = new codigodebarras();
        $bCodigoDeBarrasValido = $codigoDeBarras->esValidoCodigoDeBarras($codigoPorPost);
        if ($bCodigoDeBarrasValido) {
            $reporteTipo = substr($codigoPorPost, 0, 2);
            $nCantDigitos = substr($codigoPorPost, 2, 1);
            $id = substr($codigoPorPost, 3, $nCantDigitos);
            $em = $this->getDoctrine()->getManager();
            $eLiquidacion = $em->getRepository("MagypRendicionDeCajaBundle:Liquidacion")->find($id);
            $eRendicion = $eLiquidacion->getRendicion();
            switch ($reporteTipo) {
                case 5:
                    switch ($eRendicion->getEstado()) {
                        case Estado::ATESORERIA:
                            $cDestino = "te_rendicion_detalle";
                            break;
                        case Estado::APAGAR:
                            $cDestino = "te_rendicion_detalle";
                            break;
                        case Estado::PAGADO:
                            $cDestino = "te_rendicion_detalle";
                            break;
                        default:
                            $cDestino = "sistema_rendicion_historial";
                            $mensajeAEnviar = "La liquidacion no tiene un estado valido para el area de Tesoreria. Puede ver su histeorial para saber donde se encuentra dicho expediente.";
                            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                            $mensaje->setMensaje('Error', $mensajeAEnviar)
                                    ->Error()->NoExpira()
                                    ->Generar();
                            break;
                    }
                    $idRendicion = $eLiquidacion->getRendicion()->getId();
                    return $this->redirect($this->generateUrl($cDestino, array('idrendicion' => $idRendicion)));
                    break;
                default:
                    $mensajeAEnviar = "El codigo de barras no se corresponde con una liquidacion.";
                    $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                    $mensaje->setMensaje('Error', $mensajeAEnviar)
                            ->Error()->NoExpira()
                            ->Generar();
                    return $this->redirect($this->generateUrl('te_pistolear_home'));
                    break;
            }
            return $this->redirect($this->generateUrl('te_pistolear_home'));
        } else {
            $mensajeAEnviar = "Codigo de barras invalido.";
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
            $mensaje->setMensaje('Error', $mensajeAEnviar)
                    ->Error()->NoExpira()
                    ->Generar();
            return $this->redirect($this->generateUrl('te_pistolear_home'));
        }
    }

    /**
     * @Route("/propias", name="te_notificacion_propias")
     * @Secure(roles="ROLE_TESORERIA")
     * @Template("MagypRendicionDeCajaBundle:Tesoreria:propiastesoreria.html.twig")
     */
    public function propiasAction($tipo = "entrantes", $pagina = 1, $cantxpaginas = 20) {
        $em = $this->getDoctrine()->getManager();
//	if($tipo == "entrantes"){
//	    $qbnotificaciones = $em->getRepository('MagypRendicionDeCajaBundle:Notificacion')->qbNotificacionesEntrantes($this->getUsuario());
//	}
//	if($tipo == "salientes"){
//	    $qbnotificaciones = $em->getRepository('MagypRendicionDeCajaBundle:Notificacion')->qbNotificacionesSalientes($this->getUsuario());
//	}
        $texto = $this->getRequest()->get('notificacion_buscar');

        if ($tipo == "entrantes") {
            $qbnotificaciones = $em->getRepository('MagypRendicionDeCajaBundle:Notificacion')->qbNotificacionesEntrantesTesoreria($this->getUsuario(), $texto);
        }
        if ($tipo == "salientes") {
            $qbnotificaciones = $em->getRepository('MagypRendicionDeCajaBundle:Notificacion')->qbNotificacionesEntrantesTesoreria($this->getUsuario(), $texto);
        }

        $notificaciones = $this->Paginar($qbnotificaciones);
        $area = $this->getUsuario()->getArea();

        //$this->getCofra()->dump($notificaciones);

        return array('notificaciones' => $notificaciones, 'area' => $area, 'tipo' => $tipo);
    }

    public function Paginar($qb) {

        $paginador = $this->get('knp_paginator');
        $cantidad = $this->get('request')->query->get('cant');
        $cantidad = intval($cantidad) > 0 ? intval($cantidad) : 20;

        return $paginador->paginate(
                        $qb->getQuery(), $this->get('request')->query->get('page', 1), $cantidad
        );
    }

    /**
     * @Route("/seleccionartesoreria", name="te_seleccionar_tesoreria")
     * @Secure(roles="ROLE_TESORERIA")
     * @Template()
     */
    public function seleccionartesoreriaAction() {
        $em = $this->getDoctrine()->getManager();
        $areas = $em->getRepository('MagypRendicionDeCajaBundle:Area')->qb_areas_tesoreria();
        return array(
            'areas' => $areas,
        );
    }

    /**
     * @Route("/seleccionartesoreria/sesionar", name="te_seleccionar_sesionar")
     * @Secure(roles="ROLE_TESORERIA")
     * @Template()
     */
    public function seleccionartesoreriasesionarAction(Request $request) {
        $idArea = $request->request->get('seleccionada');

        $this->get('session')->set('areaseleccionada', $idArea);
        return $this->redirect($this->generateUrl('te_home')); 
    }

}
