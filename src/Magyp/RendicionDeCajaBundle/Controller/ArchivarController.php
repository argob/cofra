<?php

namespace Magyp\RendicionDeCajaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\Archivar;
use Magyp\RendicionDeCajaBundle\Form\ArchivarType;



use \Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Magyp\RendicionDeCajaBundle\Form\RendicionType;
use Framework\GeneralBundle\Entity\CodigoDeBarras;
use Magyp\RendicionDeCajaBundle\Entity\Estado;

/**
 * Archivar controller.
 *
 * @Route("/sistema/af/archivar")
 */
class ArchivarController extends BaseCofraController
{
    /**
     * Lists all Archivar entities.
     *
     * @Route("/", name="af_archivar_index")
     * @Secure(roles="ROLE_AF")
     * @Template()
     */
    public function indexAction()
    {
        
        //$aeArchivar = $em->getRepository('MagypRendicionDeCajaBundle:Archivar')->findAll();

        $em = $this->getDoctrine()->getManager();
		$texto = $this->getRequest()->get('archivar_buscar');
        $qbArchivadas = $em->getRepository('MagypRendicionDeCajaBundle:Archivar')->qb_archivar($texto);

		$paginador =  $this->get('knp_paginator');
		$cantidad = $this->get('request')->query->get('cantidad');
		$cantidad = intval($cantidad) > 0  ? intval($cantidad) : 10;
	
        $aeArchivar = $paginador->paginate(
			$qbArchivadas->getQuery(),
			$this->get('request')->query->get('page', 1),
			$cantidad
		 );
        		
		
        return array(
            'aarchivar' => $aeArchivar,
        );
    }

    /**
     * Displays a form to edit an existing Archivar entity.
     *
     * @Route("/{id}/modificar", name="af_archivar_modificar_role")
     * @Secure(roles="ROLE_ARCHIVAR_MODIFICAR")
     * @Template()
     */
    public function modificarAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:Archivar')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Archivar entity.');
        }

        $editForm = $this->createForm(new ArchivarType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'archivar'    => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Archivar entity.
     *
     * @Route("/{id}/update", name="af_archivar_update")
     * @Secure(roles="ROLE_ARCHIVAR_MODIFICAR")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:Archivar:modificar.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $numerocaja= $request->request->get('numerocaja');
        $liquidacionenviada= $request->request->get('liquidacionenviada');
        
        $em = $this->getDoctrine()->getManager();
        $em->refresh($this->getUsuario());
        $eArchivada = $em->getRepository("MagypRendicionDeCajaBundle:Archivar")->find($id);
        $eArchivadaAnterior = clone $eArchivada;
		
        $codigoDeBarras= new codigodebarras();
        $bCodigoDeBarrasValido= $codigoDeBarras->esValidoCodigoDeBarras( $liquidacionenviada);
        if ( $bCodigoDeBarrasValido ){
            $reporteTipo= substr ($liquidacionenviada, 0, 2 );
            $nCantDigitos= substr ($liquidacionenviada, 2, 1 );
            $idLiquidacion= substr ($liquidacionenviada, 3, $nCantDigitos );
        }else{
            $mensajeAEnviar= "El codigo de barras invalido.";
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                $mensaje->setMensaje('Error',$mensajeAEnviar)		    
                ->Error()->NoExpira()
                ->Generar();
            return $this->redirect($this->generateUrl('af_archivar_modificar_role', array('id' => $id ) ));
        }
            
        if ( $reporteTipo != 5 ){
            $mensajeAEnviar= "La impresion no es una liquidacion.";
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                $mensaje->setMensaje('Error',$mensajeAEnviar)		    
                ->Error()->NoExpira()
                ->Generar();
            return $this->redirect($this->generateUrl('af_archivar_modificar_role', array('id' => $id ) ));
        }
        
        $eLiquidacion = $em->getRepository("MagypRendicionDeCajaBundle:Liquidacion")->find( $idLiquidacion );
        
        $eRendicion = $eArchivada->getRendicion();
         
        $archivarSegunLiquidacion = $em->getRepository('MagypRendicionDeCajaBundle:Archivar')->findOneBy(array ('liquidacion' => $idLiquidacion ) );  
        if ( (count($archivarSegunLiquidacion) != 0) && ( $archivarSegunLiquidacion->getId() != $id ) ){
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
            $mensaje->setMensaje('Error',"La liquidacion que se intenta archivar se encuentra archivada en la caja ".$archivarSegunLiquidacion->getCaja().".")		    
                    ->Error()->NoExpira()
                    ->Generar();
        }else{
            if ( $eLiquidacion->getRendicion() == $eRendicion ){
                if ($eRendicion->isArchivada()){
                    if ($eArchivada ){
                        $eArchivada->setLiquidacion($eLiquidacion);
                        $eArchivada->setCaja($numerocaja);
                        $em->persist($eArchivada);
						$this->getCofra()->addEventoArchivar(\Magyp\RendicionDeCajaBundle\Entity\Evento::MODIFICIACION, $eArchivada, $eArchivadaAnterior);
                        $em->flush();
						/** saque el mensaje esta en eventos */ 
                        return $this->redirect($this->generateUrl('af_archivar_index'));
                    }else{
                        $mensajeAEnviar= "Caja invalida.";
                        $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                        $mensaje->setMensaje('Error',$mensajeAEnviar)		    
                            ->Error()->NoExpira()
                            ->Generar();
                        return $this->redirect($this->generateUrl('af_archivar_modificar_role', array('id' => $id ) ));
                    }
                }else{
                    $mensajeAEnviar= "La rendicion no esta en estado ARCHIVADA.";
                    $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                    $mensaje->setMensaje('Error',$mensajeAEnviar)		    
                        ->Error()->NoExpira()
                        ->Generar();
                    return $this->redirect($this->generateUrl('af_archivar_modificar_role', array('id' => $id ) ));
                }
            }else{
                $mensajeAEnviar= "El codigo de barras de la liquidacion no se corresponde con la rendicion elegida.";
                    $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                    $mensaje->setMensaje('Error',$mensajeAEnviar)		    
                    ->Error()->NoExpira()
                    ->Generar();
                return $this->redirect($this->generateUrl('af_archivar_modificar_role', array('id' => $id ) ));
            }
        }
        return $this->redirect($this->generateUrl('af_archivar_modificar_role', array('id' => $id ) ));
    }

    /**
     * Deletes a Archivar entity.
     *
     * @Route("/{id}/delete", name="af_archivar_delete")
     * @Secure(roles="ROLE_AF")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MagypRendicionDeCajaBundle:Archivar')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Archivar entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('af_archivar'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    
        /**
     * @Route("/prearchivar/{idrendicion}", name="af_prearchivar_crear")
     * @Secure(roles="ROLE_AF")
     * @Template("MagypRendicionDeCajaBundle:Archivar:prearchivar.html.twig")
     */
    public function rendicionArchivarCrearAction($idrendicion)
    {	    
        return array('idrendicion' => $idrendicion);
    }
    
    
   /**
     * @Route("/archivar/{idrendicion}", name="af_archivar")
     * @Secure(roles="ROLE_AF")
     * @Template("MagypRendicionDeCajaBundle:Archivar:prearchivar.html.twig")
     */
    public function rendicionArchivarAction(Request $request, $idrendicion)
    {
        $numerocaja= $request->request->get('numerocaja');
        //$liquidacionenviada= $request->request->get('liquidacionenviada');
        
        $em = $this->getDoctrine()->getManager();

        /*$codigoDeBarras= new codigodebarras();
        $bCodigoDeBarrasValido= $codigoDeBarras->esValidoCodigoDeBarras( $liquidacionenviada);
        if ( $bCodigoDeBarrasValido ){         
            $reporteTipo= substr ($liquidacionenviada, 0, 2 );
            $nCantDigitos= substr ($liquidacionenviada, 2, 1 );
            $idLiquidacion= substr ($liquidacionenviada, 3, $nCantDigitos );
        }else{
            $mensajeAEnviar= "El codigo de barras invalido.";
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                $mensaje->setMensaje('Error',$mensajeAEnviar)		    
                ->Error()->NoExpira()
                ->Generar();
            return $this->redirect($this->generateUrl('af_prearchivar_crear', array('idrendicion' => $idrendicion ) ));
        }
          
        if ( $reporteTipo != 5 ){
            $mensajeAEnviar= "La impresion no es una liquidacion.";
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                $mensaje->setMensaje('Error',$mensajeAEnviar)		    
                ->Error()->NoExpira()
                ->Generar();
            return $this->redirect($this->generateUrl('af_prearchivar_crear', array('idrendicion' => $idrendicion ) ));
        }
        
        $eLiquidacion = $em->getRepository("MagypRendicionDeCajaBundle:Liquidacion")->find( $idLiquidacion );
        
        if ( $eLiquidacion->getRendicion()->getId() == $idrendicion ){
            
            if ($eLiquidacion->getRendicion()->isAtesoreria()){

                $eArchivarLiquidacion = $em->getRepository("MagypRendicionDeCajaBundle:Archivar")->findOneBy( array ('liquidacion'=> $eLiquidacion ) );
                if (empty($eArchivarLiquidacion) ){*/
                    $eRendicion = $em->getRepository("MagypRendicionDeCajaBundle:Rendicion")->find( $idrendicion );
                    if ($eRendicion->isApagado()){
                        $eArchivarRendicion = $em->getRepository("MagypRendicionDeCajaBundle:Archivar")->findOneBy( array ('rendicion'=> $eRendicion ) );
                        if (empty($eArchivarRendicion->getCaja()) ){
                            return $this->redirect($this->generateUrl('sistema_estado_rendicion_archivada', array('idrendicion' => $idrendicion, 'numerocaja' => $numerocaja) ));
                        }else{
                            $mensajeAEnviar= "La rendicion ya se encuentra archivada en la caja ".$eArchivarRendicion->getCaja().".";
                            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                            $mensaje->setMensaje('Error',$mensajeAEnviar)		    
                                ->Error()->NoExpira()
                                ->Generar();
                            return $this->redirect($this->generateUrl('af_prearchivar_crear', array('idrendicion' => $idrendicion ) ));
                        }
                    }else{
                        $mensajeAEnviar= "La rendicion no esta en estado PAGADO.";
                        $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                        $mensaje->setMensaje('Error',$mensajeAEnviar)		    
                            ->Error()->NoExpira()
                            ->Generar();
                        return $this->redirect($this->generateUrl('af_prearchivar_crear', array('idrendicion' => $idrendicion ) ));
                    }
/*
                }else{
                    $mensajeAEnviar= "El codigo de barras de la liquidacion ingresada ya se encuentra archivada en la caja ".$eArchivarLiquidacion->getCaja().".";
                    $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                    $mensaje->setMensaje('Error',$mensajeAEnviar)		    
                        ->Error()->NoExpira()
                        ->Generar();
                    return $this->redirect($this->generateUrl('af_prearchivar_crear', array('idrendicion' => $idrendicion ) ));
                }*/
            /*}else{
                $mensajeAEnviar= "La liquidacion no esta en estado A TESORERIA.";
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                $mensaje->setMensaje('Error',$mensajeAEnviar)		    
                    ->Error()->NoExpira()
                    ->Generar();
                return $this->redirect($this->generateUrl('af_prearchivar_crear', array('idrendicion' => $idrendicion ) ));
            }*/
        /*}else{
            $mensajeAEnviar= "El codigo de barras de la liquidacion no se corresponde con la rendicion elegida.";
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                $mensaje->setMensaje('Error',$mensajeAEnviar)		    
                ->Error()->NoExpira()
                ->Generar();
            return $this->redirect($this->generateUrl('af_prearchivar_crear', array('idrendicion' => $idrendicion ) ));
        }*/
        
        return $this->redirect($this->generateUrl('af_rendiciones_archivadas'));
    }
    
    
     /**
     * @Route("/modificar/{idarchivar}", name="af_archivar_modificar")
     * @Secure(roles="ROLE_AF")
     * @Template("MagypRendicionDeCajaBundle:Archivar:modificar.html.twig")
     */
    public function prearchivarmodificarAction($idarchivar)
    {//esto es provisorio para el pasaje ( cambiar el temnplate por lo q esta aca abajo
        /* @Template("MagypRendicionDeCajaBundle:Archivar:prearchivarmodificar.html.twig")*/
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:Archivar')->find($idarchivar);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Archivar entity.');
        }

        $editForm = $this->createForm(new ArchivarType(), $entity);
        $deleteForm = $this->createDeleteForm($idarchivar);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
	
    /**
     * @Route("/{idArchivar}/eventos", name="sistema_archivar_eventos")
     * @Secure(roles="ROLE_LOG")
     * @Template()	
     */
    public function eventosAction($idArchivar)
    {
	$eventos = $this->getCofra()->getEventoArchivar($idArchivar);
	$em = $this->getDoctrine()->getManager();
	\Magyp\RendicionDeCajaBundle\Entity\LogRegistroEntidad::setEntityManager($em);
	//$em->get
	return array('eventos' => $eventos);
        
    }       	
    
}
