<?php

namespace Magyp\RendicionDeCajaBundle\Controller\Entidades;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\Evento;
use Magyp\RendicionDeCajaBundle\Form\EventoType;

/**
 * Evento controller.
 *
 * @Route("/evento")
 */
class EventoController extends Controller
{
    /**
     * Lists all Evento entities.
     *
     * @Route("/", name="evento")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MagypRendicionDeCajaBundle:Evento')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Evento entity.
     *
     * @Route("/{id}/show", name="evento_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:Evento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Evento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Evento entity.
     *
     * @Route("/new", name="evento_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Evento();
        $form   = $this->createForm(new EventoType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Evento entity.
     *
     * @Route("/create", name="evento_create")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:Evento:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Evento();
        $form = $this->createForm(new EventoType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('evento_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Evento entity.
     *
     * @Route("/{id}/edit", name="evento_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:Evento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Evento entity.');
        }

        $editForm = $this->createForm(new EventoType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Evento entity.
     *
     * @Route("/{id}/update", name="evento_update")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:Evento:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:Evento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Evento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new EventoType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('evento_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Evento entity.
     *
     * @Route("/{id}/delete", name="evento_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MagypRendicionDeCajaBundle:Evento')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Evento entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('evento'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
