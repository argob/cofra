<?php

namespace Magyp\RendicionDeCajaBundle\Controller\Entidades;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\EventoComprobante;
use Magyp\RendicionDeCajaBundle\Form\EventoComprobanteType;

/**
 * EventoComprobante controller.
 *
 * @Route("/eventocomprobante")
 */
class EventoComprobanteController extends Controller
{
    /**
     * Lists all EventoComprobante entities.
     *
     * @Route("/", name="eventocomprobante")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MagypRendicionDeCajaBundle:EventoComprobante')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a EventoComprobante entity.
     *
     * @Route("/{id}/show", name="eventocomprobante_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:EventoComprobante')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EventoComprobante entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new EventoComprobante entity.
     *
     * @Route("/new", name="eventocomprobante_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new EventoComprobante();
        $form   = $this->createForm(new EventoComprobanteType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new EventoComprobante entity.
     *
     * @Route("/create", name="eventocomprobante_create")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:EventoComprobante:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new EventoComprobante();
        $form = $this->createForm(new EventoComprobanteType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('eventocomprobante_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing EventoComprobante entity.
     *
     * @Route("/{id}/edit", name="eventocomprobante_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:EventoComprobante')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EventoComprobante entity.');
        }

        $editForm = $this->createForm(new EventoComprobanteType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing EventoComprobante entity.
     *
     * @Route("/{id}/update", name="eventocomprobante_update")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:EventoComprobante:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:EventoComprobante')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EventoComprobante entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new EventoComprobanteType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('eventocomprobante_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a EventoComprobante entity.
     *
     * @Route("/{id}/delete", name="eventocomprobante_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MagypRendicionDeCajaBundle:EventoComprobante')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find EventoComprobante entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('eventocomprobante'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
