<?php

namespace Magyp\RendicionDeCajaBundle\Controller\Entidades;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\EventoImputacion;
use Magyp\RendicionDeCajaBundle\Form\EventoImputacionType;

/**
 * EventoImputacion controller.
 *
 * @Route("/eventoimputacion")
 */
class EventoImputacionController extends Controller
{
    /**
     * Lists all EventoImputacion entities.
     *
     * @Route("/", name="eventoimputacion")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MagypRendicionDeCajaBundle:EventoImputacion')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a EventoImputacion entity.
     *
     * @Route("/{id}/show", name="eventoimputacion_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:EventoImputacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EventoImputacion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new EventoImputacion entity.
     *
     * @Route("/new", name="eventoimputacion_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new EventoImputacion();
        $form   = $this->createForm(new EventoImputacionType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new EventoImputacion entity.
     *
     * @Route("/create", name="eventoimputacion_create")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:EventoImputacion:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new EventoImputacion();
        $form = $this->createForm(new EventoImputacionType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('eventoimputacion_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing EventoImputacion entity.
     *
     * @Route("/{id}/edit", name="eventoimputacion_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:EventoImputacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EventoImputacion entity.');
        }

        $editForm = $this->createForm(new EventoImputacionType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing EventoImputacion entity.
     *
     * @Route("/{id}/update", name="eventoimputacion_update")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:EventoImputacion:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:EventoImputacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EventoImputacion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new EventoImputacionType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('eventoimputacion_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a EventoImputacion entity.
     *
     * @Route("/{id}/delete", name="eventoimputacion_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MagypRendicionDeCajaBundle:EventoImputacion')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find EventoImputacion entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('eventoimputacion'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
