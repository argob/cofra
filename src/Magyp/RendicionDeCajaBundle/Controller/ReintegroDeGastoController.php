<?php

namespace Magyp\RendicionDeCajaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\ReintegroDeGasto;
use Magyp\RendicionDeCajaBundle\Form\ReintegroDeGastoType;

use Magyp\RendicionDeCajaBundle\Entity\ReintegroDeGastoDetalle;
use Magyp\RendicionDeCajaBundle\Entity\Programa;
use Magyp\RendicionDeCajaBundle\Entity\Imputacion;

use JMS\SecurityExtraBundle\Annotation\Secure;
/**
 * ReintegroDeGasto controller.
 *
 * @Route("sistema/af/reintegrodegasto")
 */
class ReintegroDeGastoController extends BaseCofraController
{
    /**
     * Lists all ReintegroDeGasto entities.
     *
     * @Route("/", name="af_reintegrodegasto")
     * @Secure(roles="ROLE_AF")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
     
//        foreach ( $entities as $entity ){
//            $entity->setActividad(str_pad($entity->getActividad(), 2, "0", STR_PAD_LEFT));
//            $entity->setUg(str_pad($entity->getUg(), 2, "0", STR_PAD_LEFT));
//        }
        $texto = $this->getRequest()->get('reintegrodegasto_buscar');
        $qb = $em->getRepository('MagypRendicionDeCajaBundle:ReintegroDeGasto')->qb_buscar($texto);

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
     * Finds and displays a ReintegroDeGasto entity.
     *
     * @Route("/{id}/mostrar", name="af_reintegrodegasto_show")
     * @Secure(roles="ROLE_AF")
     * @Template()
     */
    public function mostrarAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $eReintegroDeGasto = $em->getRepository('MagypRendicionDeCajaBundle:ReintegroDeGasto')->find($id);

        if (!$eReintegroDeGasto) {
            throw $this->createNotFoundException('Unable to find ReintegroDeGasto entity.');
        }

        $aeReintegroDeGastoDetalle = $em->getRepository('MagypRendicionDeCajaBundle:ReintegroDeGastoDetalle')->findBy(array ('reintegrodegasto' => $id));

        $deleteForm = $this->createDeleteForm($id);

        $hash= md5($eReintegroDeGasto->getId().$eReintegroDeGasto->getExpediente().$eReintegroDeGasto->getArea());
        
        $eReintegroDeGasto->setActividad(str_pad($eReintegroDeGasto->getActividad(), 2, "0", STR_PAD_LEFT));
        $eReintegroDeGasto->setUg(str_pad($eReintegroDeGasto->getUg(), 2, "0", STR_PAD_LEFT));
        
        return array(
            'entity'      => $eReintegroDeGasto,
            'detalles'      => $aeReintegroDeGastoDetalle,
            'delete_form' => $deleteForm->createView(),
            'hash' => $hash,
            
        );
    }

    /**
     * Displays a form to create a new ReintegroDeGasto entity.
     *
     * @Route("/nuevo", name="af_reintegrodegasto_new")
     * @Secure(roles="ROLE_AF")
     * @Template()
     */
    public function nuevoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new ReintegroDeGasto();
        $form   = $this->createForm(new ReintegroDeGastoType(), $entity);

        $aePrograma = $em->getRepository("MagypRendicionDeCajaBundle:Programa")->findAll();
        
        $aeImputacion = $em->getRepository("MagypRendicionDeCajaBundle:Imputacion")->buscarImputaciones();
             
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'programas' => $aePrograma,
            'imputaciones' => $aeImputacion,
        );
    }

    /**
     * Creates a new ReintegroDeGasto entity.
     *
     * @Route("/create", name="af_reintegrodegasto_create")
     * @Secure(roles="ROLE_AF")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:ReintegroDeGasto:nuevo.html.twig")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $aProgramaDetalle= $request->request->get('programa_detalle');
        $aImputacionDetalle= $request->request->get('imputacion_detalle');
        $aSubtotalDetalle= $request->request->get('subtotal_detalle');
        
        $eReintegroDeGasto  = new ReintegroDeGasto();
        $form = $this->createForm(new ReintegroDeGastoType(), $eReintegroDeGasto);
        $form->bind($request);

        if ($form->isValid()) {
            $userCompleto = $this->getDoctrine()->getManager()->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($this->getUsuario()->getId());
            $em->refresh($userCompleto);
            $eReintegroDeGasto->setResponsable($userCompleto);
            $em->persist($eReintegroDeGasto);

            for ( $nPosDetalle= 0; $nPosDetalle < count ( $aProgramaDetalle ) ; $nPosDetalle++ ){
                $eReintegroDeGastoDetalle  = new ReintegroDeGastoDetalle();
                $eReintegroDeGastoDetalle->setImportesubtotal($aSubtotalDetalle[$nPosDetalle]);
                
                $eImputacion= $em->getRepository('MagypRendicionDeCajaBundle:Imputacion')->find($aImputacionDetalle[$nPosDetalle]); 
                
                $eReintegroDeGastoDetalle->setImputacion($eImputacion);
                
                $ePrograma= $em->getRepository('MagypRendicionDeCajaBundle:Programa')->find($aProgramaDetalle[$nPosDetalle]); 
                
                $eReintegroDeGastoDetalle->setPrograma($ePrograma);
                $eReintegroDeGastoDetalle->setReintegrodegasto($eReintegroDeGasto);
                $em->persist($eReintegroDeGastoDetalle);
                
            }
            
            $em->flush();
           

            return $this->redirect( $this->generateUrl('af_reintegrodegasto_show', array('id' => $eReintegroDeGasto->getId() ) ) );
        }
        $aePrograma = $em->getRepository("MagypRendicionDeCajaBundle:Programa")->findAll();
        $aeImputacion = $em->getRepository("MagypRendicionDeCajaBundle:Imputacion")->buscarImputaciones();
        return array(
            'entity' => $eReintegroDeGasto,
            'form'   => $form->createView(),
            'programas' => $aePrograma,
            'imputaciones' => $aeImputacion,
        );
    }

    /**
     * Displays a form to edit an existing ReintegroDeGasto entity.
     *
     * @Route("/{id}/modificar", name="af_reintegrodegasto_edit")
     * @Secure(roles="ROLE_AF")
     * @Template()
     */
    public function modificarAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $eReintegroDeGasto= $em->getRepository('MagypRendicionDeCajaBundle:ReintegroDeGasto')->find($id);

        $aeReintegroDeGastoDetalle= $em->getRepository('MagypRendicionDeCajaBundle:ReintegroDeGastoDetalle')->findBy(array ( 'reintegrodegasto'=> $id ) );
        
        $aePrograma = $em->getRepository("MagypRendicionDeCajaBundle:Programa")->findAll();
        
        $aeImputacion = $em->getRepository("MagypRendicionDeCajaBundle:Imputacion")->buscarImputaciones();
        
        
        if (!$eReintegroDeGasto) {
            throw $this->createNotFoundException('Unable to find ReintegroDeGasto entity.');
        }

        $editForm = $this->createForm(new ReintegroDeGastoType(), $eReintegroDeGasto);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'reintegrodegasto'  => $eReintegroDeGasto,
            'reintegrodegastodetalles'  => $aeReintegroDeGastoDetalle,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'programas' => $aePrograma,
            'imputaciones' => $aeImputacion,
        );

    }

    /**
     * Edits an existing ReintegroDeGasto entity.
     *
     * @Route("/{id}/update", name="af_reintegrodegasto_update")
     * @Secure(roles="ROLE_AF")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:ReintegroDeGasto:modificar.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $aProgramaDetalle= $request->request->get('programa_detalle');
        $aImputacionDetalle= $request->request->get('imputacion_detalle');
        $aSubtotalDetalle= $request->request->get('subtotal_detalle');
        $aBorrarDetalle= $request->request->get('borrar_detalle');
        $aidDetalle= $request->request->get('iddetalle');
        
        $eReintegroDeGasto = $em->getRepository('MagypRendicionDeCajaBundle:ReintegroDeGasto')->find($id);

        if (!$eReintegroDeGasto) {
            throw $this->createNotFoundException('Unable to find ReintegroDeGasto entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ReintegroDeGastoType(), $eReintegroDeGasto);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $userCompleto = $this->getDoctrine()->getManager()->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($this->getUsuario()->getId());
            $em->refresh($userCompleto);
            $eReintegroDeGasto->setResponsable($userCompleto);
            $em->persist($eReintegroDeGasto);
            
             //modifico los exsitentes
            for ( $nPosDetalle= 0 ; $nPosDetalle < count ($aidDetalle) ; $nPosDetalle++){

                $eReintegroDeGastoDetalle = $em->getRepository('MagypRendicionDeCajaBundle:ReintegroDeGastoDetalle')->find($aidDetalle[$nPosDetalle]);

                $eReintegroDeGastoDetalle->setImportesubtotal($aSubtotalDetalle[$nPosDetalle]);
                
                $eImputacion= $em->getRepository('MagypRendicionDeCajaBundle:Imputacion')->find($aImputacionDetalle[$nPosDetalle]); 
                
                $eReintegroDeGastoDetalle->setImputacion($eImputacion);
                
                $ePrograma= $em->getRepository('MagypRendicionDeCajaBundle:Programa')->find($aProgramaDetalle[$nPosDetalle]); 
                
                $eReintegroDeGastoDetalle->setPrograma($ePrograma);
                $eReintegroDeGastoDetalle->setReintegrodegasto($eReintegroDeGasto);
                $em->persist($eReintegroDeGastoDetalle);
            }
            
            //ingreso los nuevos
            for ( $nPosDetalle= count ($aidDetalle) ; $nPosDetalle < count ($aSubtotalDetalle) ; $nPosDetalle++){
                $eReintegroDeGastoDetalle  = new ReintegroDeGastoDetalle();
                $eReintegroDeGastoDetalle->setImportesubtotal($aSubtotalDetalle[$nPosDetalle]);
                
                $eImputacion= $em->getRepository('MagypRendicionDeCajaBundle:Imputacion')->find($aImputacionDetalle[$nPosDetalle]); 
                
                $eReintegroDeGastoDetalle->setImputacion($eImputacion);
                
                $ePrograma= $em->getRepository('MagypRendicionDeCajaBundle:Programa')->find($aProgramaDetalle[$nPosDetalle]); 
                
                $eReintegroDeGastoDetalle->setPrograma($ePrograma);
                $eReintegroDeGastoDetalle->setReintegrodegasto($eReintegroDeGasto);
                $em->persist($eReintegroDeGastoDetalle);
            }
            $em->flush();
            //borro los seleccionados
            for ( $nPosBorrables= 0; $nPosBorrables < $nPosDetalle; $nPosBorrables++ ){
                if ( isset ( $aBorrarDetalle[$nPosBorrables] ) ){
                    $eReintegroDeGastoDetalleABorrar = $em->getRepository('MagypRendicionDeCajaBundle:ReintegroDeGastoDetalle')->find($aBorrarDetalle[$nPosBorrables]);
                    $em->remove($eReintegroDeGastoDetalleABorrar);
                    $em->flush();
                }
            }

            return $this->redirect($this->generateUrl('af_reintegrodegasto_show', array('id' => $id)));
        }

        return $this->redirect($this->generateUrl('af_reintegrodegasto_show', array('id' => $id)));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
