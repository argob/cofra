<?php

namespace Magyp\RendicionDeCajaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\Area;
use Magyp\RendicionDeCajaBundle\Form\AreaType;
use Magyp\RendicionDeCajaBundle\Form\AreaSinResponsableType;

use JMS\SecurityExtraBundle\Annotation\Secure;
/**
 * Area controller.
 *
 * @Route("/sistema/af/area")
 */
class AreaController extends BaseCofraController
{
    /**
     * Lists all Area entities.
     *
     * @Route("/", name="af_area")
     * @Secure(roles="ROLE_AF")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $qbAreas = $em->getRepository('MagypRendicionDeCajaBundle:Area')->qb_areas();

	$paginador =  $this->get('knp_paginator');
	$cantidad = $this->get('request')->query->get('cantidad');
	$cantidad = intval($cantidad) > 0  ? intval($cantidad) : 10;
	
        $areas = $paginador->paginate(
	    $qbAreas->getQuery(),
	    $this->get('request')->query->get('page', 1),
	    $cantidad
	 );
        
        return array(
            'areas' => $areas,
        );
    }

    /**
     * Finds and displays a Area area.
     *
     * @Route("/{id}/show", name="af_area_show")
     * @Secure(roles="ROLE_AF")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $area = $em->getRepository('MagypRendicionDeCajaBundle:Area')->find($id);

        if (!$area) {
            throw $this->createNotFoundException('Unable to find Area area.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'area'      => $area,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Area area.
     *
     * @Route("/nueva", name="af_area_nueva")
     * @Secure(roles="ROLE_AF")
     * @Template()  
     */
    public function nuevaAction()
    {
        $area = new Area();
        $form   = $this->createForm(new AreaType(), $area);

        return array(
            'area' => $area,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Area area.
     *
     * @Route("/crear", name="af_area_crear")
     * @Secure(roles="ROLE_AF")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:Area:nueva.html.twig")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->clear();
        $area  = new Area();
        $form = $this->createForm(new AreaType(), $area);
        $form->bind($request);

        if ($form->isValid()) {
            $arespempty= $area->getResponsable();
            if ( $arespempty == "No tiene" ){
                $arespempty= null;
            }
            $asubrespempy= $area->getSubresponsable();
            if ( $asubrespempy == "No tiene" ){
                $asubrespempy= null;
            }
            if ( ( (!empty ( $arespempty) ) && (!empty ( $asubrespempy ) ) ) && ( ( $arespempty->getId() ) == ( $asubrespempy->getId() ) ) ){
                $cMensajeError= "El responsable y subresponsable no pueden ser el mismo usuario.";
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
                $mensaje->setMensaje('Error', $cMensajeError)		    
                    ->Error()->NoExpira()
                    ->Generar();
            }else{
                $area->setBorrado(0);
                $em->persist($area);
                $this->getCofra()->addEventoArea(\Magyp\RendicionDeCajaBundle\Entity\Evento::NUEVO, $area);
                $em->flush();
            }
	    return $this->redirect($this->generateUrl('af_area'));
            //return $this->redirect($this->generateUrl('area_area_mostrar', array('id' => $area->getId())));
        }

        return array(
            'area' => $area,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Area area.
     *
     * @Route("/{id}/editar", name="af_area_editar")
     * @Secure(roles="ROLE_AF")
     * @Template("MagypRendicionDeCajaBundle:Area:editarsinresp.html.twig")
     */
    public function editarAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $area = $em->getRepository('MagypRendicionDeCajaBundle:Area')->find($id);
        if (!$area) {
            throw $this->createNotFoundException('Unable to find Area area.');
        }
        
        $aresp= $area->getResponsable();
        $asubresp= $area->getSubResponsable();
        
        if (empty($aresp)){

            $usuariosResponsables= $em->getRepository('MagypRendicionDeCajaBundle:Usuario')->qb_responsables_disponibles(); 
        }else{

            $usuariosResponsables= $em->getRepository('MagypRendicionDeCajaBundle:Usuario')->qb_responsables_disponibles_preset($aresp);
        }
        
        if (empty($asubresp)){
            $usuariosSubResponsables= $em->getRepository('MagypRendicionDeCajaBundle:Usuario')->qb_subresponsables_disponibles();
        }else{
            $usuariosSubResponsables = $em->getRepository('MagypRendicionDeCajaBundle:Usuario')->qb_subresponsables_disponibles_preset($asubresp);
        }
        
        
        
        $editForm = $this->createForm(new AreaSinResponsableType(), $area);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'area'      => $area,
            'usuariosresponsables' => $usuariosResponsables,
            'usuariossubresponsables' => $usuariosSubResponsables,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Area area.
     *
     * @Route("/{id}/update", name="af_area_update")
     * @Secure(roles="ROLE_AF")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:Area:editarsinresp.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $em->clear();
        $area = $em->getRepository('MagypRendicionDeCajaBundle:Area')->find($id);
        $areaAnterior = clone $area;
        //$areaDBResponsable = $em->getRepository('MagypRendicionDeCajaBundle:Area')->qb_AreasDisponibles($area->getId());

        if (!$area) {
            throw $this->createNotFoundException('Unable to find Area area.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new AreaSinResponsableType(), $area);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $respaux= $request->request->get('responsable');
            $subrespaux= $request->request->get('subresponsable');
            if ($respaux != -1){
                $eUsuarioResp = $em->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($respaux);
                $area->setResponsable($eUsuarioResp);
                $arespempty= $area->getResponsable();
            }else{
                $area->setResponsable(null);
            }
            if ($subrespaux != -1){
                $eUsuarioSubResp = $em->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($subrespaux);
                $area->setSubResponsable($eUsuarioSubResp);
                $asubrespempy= $area->getSubresponsable();
            }else{
                $area->setSubResponsable(null);
            }
            if ( ( ( !empty ($arespempty) ) && (!empty ( $asubrespempy ) ) ) && ( ( $arespempty->getId() ) == ( $asubrespempy->getId() ) ) ){
                $cMensajeError= "El responsable y subresponsable no pueden ser el mismo usuario.";
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
                $mensaje->setMensaje('Error', $cMensajeError)		    
                    ->Error()->NoExpira()
                    ->Generar();
            }else{
                $em->persist($area);
                $this->getCofra()->addEventoArea(\Magyp\RendicionDeCajaBundle\Entity\Evento::MODIFICIACION, $area, $areaAnterior);
                $em->flush();
            }

            
            //return $this->redirect($this->generateUrl('area_area_editar', array('id' => $id)));
            return $this->redirect($this->generateUrl('af_area' ));
        }
        return array(
            'area'      => $area,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Area area.
     *
     * Route("/{id}/delete", name="af_area_delete")
     * @Secure(roles="ROLE_AF")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->clear();
            $area = $em->getRepository('MagypRendicionDeCajaBundle:Area')->find($id);

            if (!$area) {
                throw $this->createNotFoundException('Unable to find Area area.');
            }

            $em->remove($area);
	    $this->getCofra()->addEventoArea(\Magyp\RendicionDeCajaBundle\Entity\Evento::BORRAR, $area);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('af_area'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    /**
     * @Route("/{idArea}/eventos", name="sistema_area_eventos")
     * @Secure(roles="ROLE_LOG")
     * @Template()	
     */
    public function eventosAction($idArea)
    {
	$eventos = $this->getCofra()->getEventoArea($idArea);
	$em = $this->getDoctrine()->getManager();
	\Magyp\RendicionDeCajaBundle\Entity\LogRegistroEntidad::setEntityManager($em);
	//$em->get
	return array('eventos' => $eventos);
        
    }       
    
    
    /**
     *
     * @Route("/{id}/baja", name="af_area_baja")
     * @Secure(roles="ROLE_AF")
     * @Template()
     */
    public function bajaAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $area = $em->getRepository('MagypRendicionDeCajaBundle:Area')->find($id);
        $usuariosdelarea = $em->getRepository('MagypRendicionDeCajaBundle:Usuario')->findByArea($area);

        if (!$area) {
            throw $this->createNotFoundException('Unable to find Area area.');
        }

        return array(
            'area'      => $area,
            'usuariosdelarea'   => $usuariosdelarea
        );
    }
    
        /**
     *
     * @Route("/{id}/bajaupdate", name="af_area_bajaupdate")
     * @Secure(roles="ROLE_AF")
     * @Template()
     */
    public function bajaupdateAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $em->clear();
        $area = $em->getRepository('MagypRendicionDeCajaBundle:Area')->find($id);
    
        if (!$area) {
            throw $this->createNotFoundException('Unable to find Area area.');
        }
        
        $horaActual= new \DateTime();
        $horaActual= $horaActual->format('d/m/Y');
        $areaAbierta= $area->getNombre();
        $area->setBorrado(1);
        $area->setNombre( $area->getNombre()." - CERRADA: ".$horaActual );
        $em->persist($area);
        $this->getCofra()->addEventoArea(\Magyp\RendicionDeCajaBundle\Entity\Evento::BAJA, $area);
        
        $areaNueva= new Area();
        $areaNueva->setActividad($area->getActividad());
        $areaNueva->setFf($area->getFf());
        $areaNueva->setMonto($area->getMonto());
        $areaNueva->setPrograma($area->getPrograma());
        $areaNueva->setUg($area->getUg());
        $areaNueva->setBorrado(0);
        $areaNueva->setNombre( $areaAbierta );
        $em->persist($areaNueva);
        $this->getCofra()->addEventoArea(\Magyp\RendicionDeCajaBundle\Entity\Evento::NUEVOPORBAJA, $areaNueva);
        
        $em->flush();
        $cMensajeError= "Se dio de baja el area ".$area->getNombre().".";
        $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
        $mensaje->setMensaje('Exito', $cMensajeError)		    
        ->Exito()
        ->Generar();
        return $this->redirect($this->generateUrl('af_area'));
    }
    
     /**
     * Lists all Area entities.
     *
     * @Route("/cerradas", name="af_area_cerradas")
     * @Secure(roles="ROLE_AF")
     * @Template()
     */
    public function cerradasAction()
    {
        $em = $this->getDoctrine()->getManager();
        $qbAreas = $em->getRepository('MagypRendicionDeCajaBundle:Area')->qb_areas_cerradas();

        $paginador =  $this->get('knp_paginator');
        $cantidad = $this->get('request')->query->get('cantidad');
        $cantidad = intval($cantidad) > 0  ? intval($cantidad) : 10;
	
        $areas = $paginador->paginate(
	    $qbAreas->getQuery(),
	    $this->get('request')->query->get('page', 1),
	    $cantidad
	 );
        
        return array(
            'areas' => $areas,
        );
    }
    

}
