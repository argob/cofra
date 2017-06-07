<?php

namespace Magyp\RendicionDeCajaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\AnticipoDeGasto;
use Magyp\RendicionDeCajaBundle\Form\AnticipoDeGastoType;

use JMS\SecurityExtraBundle\Annotation\Secure;
/**
 * AnticipoDeGasto controller.
 *
 * @Route("/sistema/af/anticipodegasto")
 */
class AnticipoDeGastoController extends BaseCofraController
{
    /**
     * Lists all AnticipoDeGasto entities.
     *
     * @Route("/", name="af_anticipodegasto")
     * @Secure(roles="ROLE_AF")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        //$entities = $em->getRepository('MagypRendicionDeCajaBundle:AnticipoDeGasto')->findAll();
        $texto = $this->get('request')->query->get('anticipo_dg_buscar');
        //$qb = $em->getRepository('MagypRendicionDeCajaBundle:AnticipoDeGasto')->qb_todas();
        $qb = $em->getRepository('MagypRendicionDeCajaBundle:AnticipoDeGasto')->qb_buscar($texto);
        $entities = $qb->getQuery()->getResult();
        
        
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
     * Finds and displays a AnticipoDeGasto entity.
     *
     * @Route("/{id}/mostrar", name="af_anticipodegasto_show")
     * @Secure(roles="ROLE_AF")
     * @Template()
     */
    public function mostrarAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:AnticipoDeGasto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AnticipoDeGasto entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        $eAnticipoDeGasto = $em->getRepository("MagypRendicionDeCajaBundle:AnticipoDeGasto")->find($id);
        $hash= md5($eAnticipoDeGasto->getId().$eAnticipoDeGasto->getExpediente().$eAnticipoDeGasto->getArea());
        
        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'hash' => $hash,
        );
    }

    /**
     * Displays a form to create a new AnticipoDeGasto entity.
     *
     * @Route("/nuevo", name="af_anticipodegasto_new")
     * @Secure(roles="ROLE_AF")
     * @Template()
     */
    public function nuevoAction()
    {
        $entity = new AnticipoDeGasto();
        $form   = $this->createForm(new AnticipoDeGastoType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new AnticipoDeGasto entity.
     *
     * @Route("/create", name="af_anticipodegasto_create")
     * @Secure(roles="ROLE_AF")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:AnticipoDeGasto:nuevo.html.twig")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity  = new AnticipoDeGasto();
        $form = $this->createForm(new AnticipoDeGastoType(), $entity);
        $form->bind($request);
        if ($form->isValid()) {
            $userCompleto = $this->getDoctrine()->getManager()->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($this->getUsuario()->getId());
            $em->refresh($userCompleto);
            $entity->setResponsable($userCompleto);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('af_anticipodegasto_show', array('id' => $entity->getId())));
        }
        $cMensajeError= "El valor del monto es incorrecto.";
        $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
        $mensaje->setMensaje('Error', $cMensajeError)		    
            ->Error()->NoExpira()
            ->Generar();
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing AnticipoDeGasto entity.
     *
     * @Route("/{id}/modificar", name="af_anticipodegasto_edit")
     * @Secure(roles="ROLE_AF")
     * @Template()
     */
    public function modificarAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:AnticipoDeGasto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AnticipoDeGasto entity.');
        }

        $editForm = $this->createForm(new AnticipoDeGastoType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing AnticipoDeGasto entity.
     *
     * @Route("/{id}/update", name="af_anticipodegasto_update")
     * @Secure(roles="ROLE_AF")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:AnticipoDeGasto:modificar.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $em->clear();
        $entity = $em->getRepository('MagypRendicionDeCajaBundle:AnticipoDeGasto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AnticipoDeGasto entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new AnticipoDeGastoType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $userCompleto = $this->getDoctrine()->getManager()->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($this->getUsuario()->getId());
            $em->refresh($userCompleto);
            $entity->setResponsable($userCompleto);
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('af_anticipodegasto_show', array('id' => $id)));
        }
        $cMensajeError= "El valor del monto es incorrecto.";
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
                $mensaje->setMensaje('Error', $cMensajeError)		    
                    ->Error()->NoExpira()
                    ->Generar();
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
