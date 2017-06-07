<?php

namespace Magyp\RendicionDeCajaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\Proveedor;
use Magyp\RendicionDeCajaBundle\Form\ProveedorType;
use Magyp\RendicionDeCajaBundle\Entity\EventoProveedor;

use JMS\SecurityExtraBundle\Annotation\Secure;

use Framework\GeneralBundle\Entity\FuncionalidadesCuit;

/**
 * Proveedor controller.
 * 
 * @Route("/sistema/proveedor")
 */
class ProveedorController extends BaseCofraController
{
    /**
     * Lists all Proveedor entities.
     *
     * @Secure(roles="ROLE_PROVEEDOR")
     * @Route("/", name="proveedor")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        //$entities = $this->Entidad('Proveedor')->buscarProveedores();
        $idproveedor = $this->get('request')->query->get('idproveedor');
        if(intval($idproveedor)>0){
            $query = $em->getRepository('MagypRendicionDeCajaBundle:Proveedor')->qbProveedor($idproveedor);	

        }else
        {
            $query = $em->getRepository('MagypRendicionDeCajaBundle:Proveedor')->qbBuscar($this->get('request')->query->get('buscar'));	
        }
        $paginador =  $this->get('knp_paginator');
        $cantidad = $this->get('request')->query->get('cantidad');
        $cantidad = intval($cantidad) > 0  ? intval($cantidad) : 30;

        $paginador = $paginador->paginate(
            $query,
            $this->get('request')->query->get('page', 1),
            $cantidad
         );

        $proveedores = $paginador;
        $proveedor = new Proveedor();
        $form   = $this->createForm(new ProveedorType(), $proveedor);
        return array(
            'proveedores' => $proveedores,
            'proveedor' => $proveedor,
            'agregar'   => $form->createView(),
            'modificar'   => $form->createView(),
            'borrar'   => $form->createView(),
            'idarea' => $this->getUsuario()->getArea()->getId()
        );
        
    }

    /**
     * Finds and displays a Proveedor entity.
     *
     * @Secure(roles="ROLE_PROVEEDOR")
     * @Route("/{id}/show", name="proveedor_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $proveedor = $em->getRepository('MagypRendicionDeCajaBundle:Proveedor')->find($id);

        if (!$proveedor) {
            throw $this->createNotFoundException('Unable to find Proveedor entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $proveedor,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a new Proveedor entity.
     * 
     * @Route("/create", name="proveedor_create")
     * @Secure(roles="ROLE_PROVEEDOR")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:Proveedor:index.html.twig")
     */
    public function createAction(Request $request)
    {
        $proveedor = new Proveedor();
        $form = $this->createForm(new ProveedorType(), $proveedor);

        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
	    $usuario = $this->getUsuario();
			
            $proveedor->setArea($usuario->getArea());
            
            $eProveedor = $em->getRepository('MagypRendicionDeCajaBundle:Proveedor')->findBy(array ('borrado' => 0) );
            $bNotEstaRepetido= true;
            
            foreach ( $eProveedor as $proveedorBusqueda ){
                $bNotEstaRepetido= $bNotEstaRepetido && ( $proveedorBusqueda->getCuit() != $proveedor->getCuit() );
            }
            
            if ( $bNotEstaRepetido == false ){
                $cMensajeError= "CUIT existente correspondiente a proveedor cargado por el area: ".$proveedor->getArea().".";
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
                $mensaje->setMensaje('Error', $cMensajeError)		    
                    ->Error()->NoExpira()
                    ->Generar();
            }else{
                
                $funcionalidadesCuit= new funcionalidadescuit();
                $bCuitValido= $funcionalidadesCuit->validaCuit($proveedor->getCuit());
                
                if ( $bCuitValido == false ){
                    $cMensajeError= "El CUIT ingresado es invalido.";
                    $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
                    $mensaje->setMensaje('Error', $cMensajeError)		    
                    ->Error()->NoExpira()
                    ->Generar();
                }else{
                    $em->persist($proveedor);
                    $userCompleto= $this->getUsuario();
                    $em->refresh($userCompleto);
                    $eventoProveedor = new EventoProveedor($userCompleto, EventoProveedor::NUEVO, $proveedor,null,$this->getRequest());
                    $em->persist($eventoProveedor);
                    $em->flush();
                    $cMensajeError= "Se creo el proveedor ".$proveedor->getDescripcion().".";
                    $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                    $mensaje->setMensaje('Exito', $cMensajeError)		    
                    ->Exito()
                    ->Generar();
                }
            }
            return $this->redirect($this->generateUrl('proveedor'));
        }
        $cMensajeError= "Campo CUIT o Razon Social con caracteres invalidos.";
        $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
        $mensaje->setMensaje('Error', $cMensajeError)		    
            ->Error()->NoExpira()
            ->Generar();
        return $this->redirect($this->generateUrl('proveedor'));
    }

    /**
     * Edits an existing Proveedor entity.
     *

     * @Route("/update", name="proveedor_update")
     * @Secure(roles="ROLE_PROVEEDOR")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:Proveedor:edit.html.twig")
     */
    public function updateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id_modificar');
        $proveedor = $em->getRepository('MagypRendicionDeCajaBundle:Proveedor')->find($id);
		$proveedorAnterior = clone $proveedor;
        $usuario = $this->getDoctrine()->getManager()->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($this->getUsuario()->getId());
        $elUsuarioEsAF = $this->container->get('security.context')->isGranted('ROLE_AF');  
        if ( ( $proveedor->getArea() == $usuario->getArea() ) || ( $elUsuarioEsAF == true)   ){
            
            $eProveedor = $em->getRepository('MagypRendicionDeCajaBundle:Proveedor')->findBy(array ('borrado' => 0) );
            
            $deleteForm = $this->createDeleteForm($id);
            $editForm = $this->createForm(new ProveedorType(), $proveedor);
            $editForm->bind($request);
            
            $bNotEstaRepetido= true;
            
            foreach ( $eProveedor as $proveedorBusqueda ){
                if ( $bNotEstaRepetido == true ){
                    $bNotEstaRepetido= $bNotEstaRepetido && ( $proveedorBusqueda->getCuit() != $proveedor->getCuit() );
                     if ( $bNotEstaRepetido == false ){
                        if ( $proveedorBusqueda->getId() == $proveedor->getId() ){
                            $bNotEstaRepetido= true;
                        }
                    }
                }
            }       
            if ( $bNotEstaRepetido == false ){
                $cMensajeError= "El CUIT ingresado ya se encuentra cargado.";
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
                $mensaje->setMensaje('Error', $cMensajeError)		    
                    ->Error()->NoExpira()
                    ->Generar();
                return $this->redirect($this->generateUrl('proveedor'));
            }else{
                $funcionalidadesCuit= new funcionalidadescuit();
                $bCuitValido= $funcionalidadesCuit->validaCuit($proveedor->getCuit());
                if ( $bCuitValido == false ){
                    $cMensajeError= "El CUIT ingresado es invalido.";
                    $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
                    $mensaje->setMensaje('Error', $cMensajeError)		    
                    ->Error()->NoExpira()
                    ->Generar();
                    return $this->redirect($this->generateUrl('proveedor'));
                }else{
                    if (!$proveedor) {
                        throw $this->createNotFoundException('Unable to find Proveedor entity.');
                    }
                    
                    if ($editForm->isValid()) {
                        $em->refresh($usuario);
                        $proveedor->setArea($usuario->getArea());
                        $em->persist($proveedor);
						$this->getCofra()->addEventoProveedor(EventoProveedor::MODIFICIACION, $proveedor, $proveedorAnterior);
                        $em->flush();
                        $cMensajeError= "Se edito el proveedor ".$proveedor->getDescripcion().".";
                        $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                        $mensaje->setMensaje('Exito', $cMensajeError)		    
                        ->Exito()
                        ->Generar();
                        return $this->redirect($this->generateUrl('proveedor'));
                    }
                }
            }
        }else{
            $cMensajeError= "No es posible modificar el proveedor, el mismo solo puede ser modificado por un usuario del area de ".$proveedor->getArea()." .";
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
            $mensaje->setMensaje('Error', $cMensajeError)		    
                    ->Error()->NoExpira()
                    ->Generar();
            return $this->redirect($this->generateUrl('proveedor'));
        }
        
        $cMensajeError= "Campo CUIT o Razon Social con caracteres invalidos.";
        $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
        $mensaje->setMensaje('Error', $cMensajeError)		    
            ->Error()->NoExpira()
            ->Generar();
        return $this->redirect($this->generateUrl('proveedor'));
    }

    /**
     * Deletes a Proveedor entity.
     *

     * @Route("/delete", name="proveedor_delete")
     * @Secure(roles="ROLE_PROVEEDOR")
     * @Method("POST")
     */
    
    public function deleteAction(Request $request)
    {
        $id = $request->get('id_borrar');
        $em = $this->getDoctrine()->getManager();
        $proveedor = $em->getRepository('MagypRendicionDeCajaBundle:Proveedor')->find($id);

        if (!$proveedor) {
            throw $this->createNotFoundException('Unable to find Proveedor entity.');
        }
        $cMensajeError= "Se borro el proveedor ".$proveedor->getDescripcion().".";
        $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
        $mensaje->setMensaje('Exito', $cMensajeError)		    
        ->Exito()
        ->Generar();
        $em->remove($proveedor);
        $em->flush();
        return $this->redirect($this->generateUrl('proveedor'));
    }
     

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
	
    /**
     * @Route("/{id}/eventos", name="sistema_proveedor_eventos")
     * @Secure(roles="ROLE_LOG")
     * @Template()	
     */
    public function eventosAction($id)
    {
	$em = $this->getDoctrine()->getManager();
        $eventos = $em->getRepository('MagypRendicionDeCajaBundle:EventoProveedor')->findByProveedor($id);
		
	\Magyp\RendicionDeCajaBundle\Entity\LogRegistroEntidad::setEntityManager($em);
	foreach($eventos as $evento){

	}	
	return array('eventos' => $eventos);
    }

    /**
     * @Secure(roles="ROLE_PROVEEDOR")
     * @Route("/{id}/borrar", name="proveedor_borrar")
     * 
     */
    
    public function borrarAction($id)
    {
		$elUsuarioEsAF = $this->getUsuario()->esRoleAf();
        if ($elUsuarioEsAF == true){
            $em = $this->getDoctrine()->getManager();
			$em->clear();
            $proveedor = $em->getRepository('MagypRendicionDeCajaBundle:Proveedor')->find($id);

            if (!$proveedor) {
                throw $this->createNotFoundException('Unable to find Proveedor entity.');
            }
            $proveedor->setBorrado(1);
			$em->persist($proveedor);
//            $eventoProveedor = new EventoProveedor($this->getUsuario(), EventoProveedor::BORRAR, $proveedor,null,$this->getRequest());
//            $em->persist($eventoProveedor);        
			$this->getCofra()->addEventoProveedor(EventoProveedor::BORRAR, $proveedor);
            $em->flush();
            $cMensajeError= "Se borro el proveedor ".$proveedor->getDescripcion().".";
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
            $mensaje->setMensaje('Exito', $cMensajeError)		    
            ->Exito()
            ->Generar();
        }else{
            $cMensajeError= "Solo un usuario de Administracion Financiera puede borrar un proveedor.";
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
            $mensaje->setMensaje('Error', $cMensajeError)		    
                    ->Error()->NoExpira()
                    ->Generar();
        }
		
		
      return $this->redirect($this->generateUrl('proveedor'));
    }	
    
    /**
     * @Secure(roles="ROLE_PROVEEDOR")
     * @Route("/{idproveedor}/restaurar", name="sistema_proveedor_restaurar")
     * 
     */
    
    public function restaurarAction($idproveedor)
    {
        $em = $this->getDoctrine()->getManager();
		$em->clear();
        $proveedor = $em->getRepository('MagypRendicionDeCajaBundle:Proveedor')->find($idproveedor);

        if (!$proveedor) {
            throw $this->createNotFoundException('Unable to find Proveedor entity.');
        }
        
        if ( ( $proveedor->getArea() == $this->getUsuario()->getArea()) || ( $this->getUsuario()->esRoleAF()) ){
            $proveedor->setBorrado(0);
			$this->getCofra()->addEventoProveedor(EventoProveedor::RESTAURAR, $proveedor);
//            $eventoProveedor = new EventoProveedor($this->getUsuario(), EventoProveedor::RESTAURAR, $proveedor,null,$this->getRequest());
//            $em->persist($eventoProveedor);        

            $em->persist($proveedor);
            $em->flush();
            $cMensajeError= "Se restauro el proveedor ".$proveedor->getDescripcion().".";
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
            $mensaje->setMensaje('Exito', $cMensajeError)		    
            ->Exito()
            ->Generar();
        }else{
            $cMensajeError= "No se puede restaurar este proveedor, ya que usted no es un usuario del area ".$proveedor->getArea().".";
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
            $mensaje->setMensaje('Error', $cMensajeError)		    
                    ->Error()->NoExpira()
                    ->Generar();
        }

        return $this->redirect($this->generateUrl('proveedor'));
    }	
    
    
    /**
     * @Secure(roles="ROLE_PROVEEDOR")
     * @Route("/papelera/{idarea}", name="sistema_proveedor_papelera")
     * @Template("MagypRendicionDeCajaBundle:Proveedor:papelera.html.twig")
     */
    public function papeleraAction($idarea)
    {
            
            // borrar para que usuario administrador pueda leer otras areas
            $idarea = $this->getUsuario()->getArea()->getId(); 
            $em = $this->getDoctrine()->getManager();
            if ( $this->getUsuario()->esRoleAF()){
                $proveedores = $em->getRepository('MagypRendicionDeCajaBundle:Proveedor')->buscarBorradosOrdenadosAf($idarea);
            }else{
                $proveedores = $em->getRepository('MagypRendicionDeCajaBundle:Proveedor')->buscarBorradosOrdenados($idarea);
            }
        return array(
            'proveedores' => $proveedores, 
            );
	}
        

	public function Entidad($entidad){

	    $em = $this->getDoctrine()->getManager();
	    return $em->getRepository("MagypRendicionDeCajaBundle:{$entidad}");	
	}
    
}
