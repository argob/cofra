<?php

namespace Magyp\RendicionDeCajaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\Imputacion;
use Magyp\RendicionDeCajaBundle\Form\ImputacionType;
use Magyp\RendicionDeCajaBundle\Entity\EventoImputacion;

use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Imputacion controller.
 *
 * @Route("/sistema/imputacion")
 */
class ImputacionController extends BaseCofraController
{
    /**
     * Lists all Imputacion imputaciones.
     *
     * @Secure(roles="ROLE_IMPUTACION")
     * @Route("/", name="imputacion")
     * @Template()
     */
    public function indexAction($pagina = 1, $cantidad = 10)
    {
        $em = $this->getDoctrine()->getManager();
	//$query = $em->getRepository('MagypRendicionDeCajaBundle:Imputacion')->qb_buscarImputaciones();
	$idimputacion = $this->get('request')->query->get('idimputacion');
	if(intval($idimputacion)>0){
	    $query = $em->getRepository('MagypRendicionDeCajaBundle:Imputacion')->qbImputacion($idimputacion);	
	    
	}else
	{
	    $query = $em->getRepository('MagypRendicionDeCajaBundle:Imputacion')->qbBuscar($this->get('request')->query->get('buscar'));	
	}
	$paginador =  $this->get('knp_paginator');
	$cantidad = $this->get('request')->query->get('cantidad');
	$cantidad = intval($cantidad) > 0  ? intval($cantidad) : 30;

	$paginador = $paginador->paginate(
	    $query,
	    $this->get('request')->query->get('page', 1),
	    $cantidad
	 );
	
	//$paginador = array();
	$imputacion = new Imputacion();
        $form   = $this->createForm(new ImputacionType(), $imputacion);
        $idarea = $this->getUsuario()->getArea()->getId();
		
        return array(
            'paginador' => $paginador,
            'entity' => $imputacion,
            'agregar'   => $form->createView(),
            'modificar'   => $form->createView(),
            'borrar'   => $form->createView(),
            'idarea'    => $idarea,
        );
        
    }

    /**
     * Finds and displays a Imputacion entity.
     *
     * @Secure(roles="ROLE_IMPUTACION")
     * @Route("/{id}/show", name="imputacion_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $imputacion = $em->getRepository('MagypRendicionDeCajaBundle:Imputacion')->find($id);

        if (!$imputacion) {
            throw $this->createNotFoundException('Unable to find Imputacion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $imputacion,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a new Imputacion entity.
     *
     * @Route("/create", name="imputacion_create")
     * @Secure(roles="ROLE_IMPUTACION")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:Imputacion:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $imputacion  = new Imputacion();
        $form = $this->createForm(new ImputacionType(), $imputacion);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $imputacionRepetida= $this->getDoctrine()->getManager()->getRepository('MagypRendicionDeCajaBundle:Imputacion')->findBy( array('codigo' => $imputacion->getCodigo(), 'borrado' => false));
            
            if (empty ($imputacionRepetida)){
                $usuario = $this->getDoctrine()->getManager()->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($this->getUsuario()->getId());
                $imputacion->setArea($usuario->getArea());
                $em->persist($imputacion);
                $this->getCofra()->addEventoImputacion(EventoImputacion::NUEVO, $imputacion);
                $em->flush();
                $cMensajeError= "Se creo la imputacion ".$imputacion->getCodigo().".";
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                $mensaje->setMensaje('Exito', $cMensajeError)		    
                ->Exito()
                ->Generar();
                return $this->redirect($this->generateUrl('imputacion'));
            }else{
                $cMensajeError= "El codigo de imputacion ya esta registrado.";
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
                $mensaje->setMensaje('Error', $cMensajeError)		    
                    ->Error()->NoExpira()
                    ->Generar();
            }
            
            
            //$em->clear();
            
        }else{
            $cMensajeError= "Campo/s invalido/s.";
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
            $mensaje->setMensaje('Error', $cMensajeError)		    
                ->Error()->NoExpira()
                ->Generar();
        }
        
       
        return $this->redirect($this->generateUrl('imputacion'));
    }

    /**
     * Edits an existing Imputacion entity.
     *
     * @Route("/update", name="imputacion_update")
     * @Secure(roles="ROLE_IMPUTACION")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:Imputacion:edit.html.twig")
     */
    public function updateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
		$em->clear();
        $id = $request->get('id_modificar');
        $imputacion = $em->getRepository('MagypRendicionDeCajaBundle:Imputacion')->find($id);
		$imputacionAnterior = clone $imputacion;
		
        if (!$imputacion) {
            throw $this->createNotFoundException('Unable to find Imputacion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ImputacionType(), $imputacion);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            
            $imputacionRepetida= $this->getDoctrine()->getManager()->getRepository('MagypRendicionDeCajaBundle:Imputacion')->findBy( array('codigo' => $imputacion->getCodigo(), 'borrado' => false));
            
            if (empty ($imputacionRepetida) || ( (count($imputacionRepetida) == 1) && ($imputacionRepetida[0]->getId() == $imputacion->getId() ) ) ){
                $usuario = $this->getDoctrine()->getManager()->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($this->getUsuario()->getId());
                $imputacion->setArea($usuario->getArea());
                $em->persist($imputacion);
                $this->getCofra()->addEventoImputacion(EventoImputacion::MODIFICIACION, $imputacion, $imputacionAnterior);
            //$eventoImputacion = new EventoImputacion($this->getUsuario(), EventoImputacion::MODIFICIACION, $imputacion,$imputacionAnterior);
            //$em->persist($eventoImputacion);
                $em->flush();
                $cMensajeError= "Se edito la imputacion ".$imputacion->getCodigo().".";
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                $mensaje->setMensaje('Exito', $cMensajeError)		    
                ->Exito()
                ->Generar();
                return $this->redirect($this->generateUrl('imputacion'));
            }else{
                $cMensajeError= "El codigo de imputacion ya esta registrado.";
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
                $mensaje->setMensaje('Error', $cMensajeError)		    
                    ->Error()->NoExpira()
                    ->Generar();
            }
        }else{
            $cMensajeError= "Campo/s invalido/s.";
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
            $mensaje->setMensaje('Error', $cMensajeError)		    
                ->Error()->NoExpira()
                ->Generar();
        }
        return $this->redirect($this->generateUrl('imputacion'));
    }

    /**
     * Deletes a Imputacion entity.
     *
     * @Route("/delete", name="imputacion_delete")
     * @Secure(roles="ROLE_IMPUTACION")
     * @Method("POST")
     */
    
    public function deleteAction(Request $request)
    {
        $id = $request->get('id_borrar');
        $em = $this->getDoctrine()->getManager();
        $imputacion = $em->getRepository('MagypRendicionDeCajaBundle:Imputacion')->find($id);

        if (!$imputacion) {
            throw $this->createNotFoundException('Unable to find Imputacion entity.');
        }
        $em->remove($imputacion);
        $em->flush();

        return $this->redirect($this->generateUrl('imputacion'));
    }
     
    
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    /**
     * @Route("/{id}/eventos", name="sistema_imputacion_eventos")
     * @Secure(roles="ROLE_LOG")
     * 
     * @Template()	
     */
    public function eventosAction($id)
    {
	$em = $this->getDoctrine()->getManager();
        $eventos = $em->getRepository('MagypRendicionDeCajaBundle:EventoImputacion')->findByImputacion($id);
	\Magyp\RendicionDeCajaBundle\Entity\LogRegistroEntidad::setEntityManager($em);
	return array('eventos' => $eventos);

    }

    /**
     * @Secure(roles="ROLE_IMPUTACION")
     * @Route("/{id}/borrar", name="imputacion_borrar")
     */
    
    public function borrarAction($id)
    {
        
        $em = $this->getDoctrine()->getManager();
        $imputacion = $em->getRepository('MagypRendicionDeCajaBundle:Imputacion')->find($id);

        if (!$imputacion) {
            throw $this->createNotFoundException('Unable to find Imputacion entity.');
        }
        $imputacion->setBorrado(1);
        $em->persist($imputacion);
        $this->getCofra()->addEventoImputacion(EventoImputacion::BORRAR, $imputacion);
        $em->flush();
        $cMensajeError= "Se borro la imputacion ".$imputacion->getCodigo().".";
        $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
        $mensaje->setMensaje('Exito', $cMensajeError)		    
        ->Exito()
        ->Generar();
        return $this->redirect($this->generateUrl('imputacion'));
    }	

    /**
     * @Secure(roles="ROLE_IMPUTACION")
     * @Route("/{idimputacion}/restaurar", name="sistema_imputacion_restaurar")
     */
    
    public function restaurarAction($idimputacion)
    {
        
        $em = $this->getDoctrine()->getManager();
        $imputacion = $em->getRepository('MagypRendicionDeCajaBundle:Imputacion')->find($idimputacion);
        
        $imputacionRepetida= $this->getDoctrine()->getManager()->getRepository('MagypRendicionDeCajaBundle:Imputacion')->findBy( array('codigo' => $imputacion->getCodigo(), 'borrado' => false));
        if (empty ($imputacionRepetida) || ( (count($imputacionRepetida) == 1) && ($imputacionRepetida[0]->getId() == $imputacion->getId() ) ) ){
            if (!$imputacion) {
                throw $this->createNotFoundException('Unable to find Imputacion entity.');
            }
            $imputacion->setBorrado(0);
            $em->persist($imputacion);
            $this->getCofra()->addEventoImputacion(EventoImputacion::RESTAURAR, $imputacion);
            $em->flush();
            $cMensajeError= "Se restauro la imputacion ".$imputacion->getCodigo().".";
                    $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                    $mensaje->setMensaje('Exito', $cMensajeError)		    
                        ->Exito()
                        ->Generar();
        }else{
                    $cMensajeError= "El codigo de imputacion ya esta registrado, por ello no se puede restaurar.";
                    $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                    $mensaje->setMensaje('Error', $cMensajeError)		    
                        ->Error()->NoExpira()
                        ->Generar();
        }
        return $this->redirect($this->generateUrl('imputacion'));
    }	

    /**
     *
     * @Route("/papelera/{idarea}", name="sistema_imputacion_papelera")
     * @Secure(roles="ROLE_IMPUTACION")
     * @Template("MagypRendicionDeCajaBundle:Imputacion:papelera.html.twig")
     */
    public function papeleraAction($idarea)
    {            
            // borrar para que usuario administrador pueda leer otras areas
            $idarea = $this->getUsuario()->getArea()->getId(); 
            $em = $this->getDoctrine()->getManager();
            $imputaciones = $em->getRepository('MagypRendicionDeCajaBundle:Imputacion')->buscarBorradosOrdenados($idarea);

        return array(
            'imputaciones' => $imputaciones, 
            );
	}
    /**
     *
     * @Route("/referencias", name="sistema_imputacion_referencia")
     * @Secure(roles="ROLE_RENDICION")
     * @Template()
     */
    public function referenciasAction()
    {            
		$em = $this->getDoctrine()->getManager();
		//$palabrasclaves = $palabras;
		//$palabrasclaves = 'disco';
		$seleccionada = $this->getRequest()->get('seleccionada');
//		var_dump($seleccionada);
		if(!empty($seleccionada)){
				$numero =$this->getRequest()->get('idimputacion');
//				var_dump($numero);
				return $this->forward('MagypRendicionDeCajaBundle:Imputacion:redireccion',array('numero' => $numero));
		}
		$palabras = $this->getRequest()->get('palabras-referencia');		
		if(!empty($palabras)){
			if(strlen($palabras)<4 and $palabras != ''){
			$this->getCofra()->crearMensajedeError('Error', "Las busqueda debe tener al menos 4 caracteres");
			$palabras = null;
			}else{
				$resp = $em->getRepository('MagypRendicionDeCajaBundle:Referenciaimputacion')->buscarReferencias($palabras);
				//$this->getCofra()->dump($resp,5);
				if(empty($resp)){
					$this->getCofra()->crearMensajedeError('Error', "No se encontraron resultados");
					return array('palabras' => null);
				}else
				{
					$imputaciones = $resp[0];
					$referencias=$resp;
				}
				return array('referencias' => $referencias,'palabras' =>$palabras);
			}
		}
		
		
       // return array();
        return array('palabras' => null);
        //return array('referencias' => $referencias,'palabras' =>$palabras);
	}
    /**
     *
     * @Route("/referencias/redireccion", name="imputacion_referencia_redireccion")
     * @Route("/referencias/redireccion/{numero}", name="imputacion_referencia_redireccion_numero")
     * @Template()
     */
    public function redireccionAction($numero = 1)
    {            		
		//echo 'paso';
        return array('numero' => $numero);        
	}
    
    
}

