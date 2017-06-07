<?php

namespace Magyp\RendicionDeCajaBundle\Controller\Entidades;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\EventoRendicion;
use Magyp\RendicionDeCajaBundle\Form\EventoRendicionType;

/**
 * EventoRendicion controller.
 *
 * @Route("/eventorendicion")
 */
class EventoRendicionController extends Controller
{
    /**
     * Lists all EventoRendicion entities.
     *
     * @Route("/", name="eventorendicion")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MagypRendicionDeCajaBundle:EventoRendicion')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a EventoRendicion entity.
     *
     * @Route("/{id}/show", name="eventorendicion_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:EventoRendicion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EventoRendicion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new EventoRendicion entity.
     *
     * @Route("/new", name="eventorendicion_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new EventoRendicion();
        $form   = $this->createForm(new EventoRendicionType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new EventoRendicion entity.
     *
     * @Route("/create", name="eventorendicion_create")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:EventoRendicion:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new EventoRendicion();
        $form = $this->createForm(new EventoRendicionType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('eventorendicion_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing EventoRendicion entity.
     *
     * @Route("/{id}/edit", name="eventorendicion_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:EventoRendicion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EventoRendicion entity.');
        }

        $editForm = $this->createForm(new EventoRendicionType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing EventoRendicion entity.
     *
     * @Route("/{id}/update", name="eventorendicion_update")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:EventoRendicion:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:EventoRendicion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find EventoRendicion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new EventoRendicionType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('eventorendicion_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a EventoRendicion entity.
     *
     * @Route("/{id}/delete", name="eventorendicion_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MagypRendicionDeCajaBundle:EventoRendicion')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find EventoRendicion entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('eventorendicion'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
