<?php

namespace Magyp\RendicionDeCajaBundle\Controller\Entidades;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\ReintegroDeGastoDetalle;
use Magyp\RendicionDeCajaBundle\Form\ReintegroDeGastoDetalleType;

/**
 * ReintegroDeGastoDetalle controller.
 *
 * @Route("/reintegrodegastodetalle")
 */
class ReintegroDeGastoDetalleController extends Controller
{
    /**
     * Lists all ReintegroDeGastoDetalle entities.
     *
     * @Route("/", name="sistema_entidades_reintegrodegastodetalle")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MagypRendicionDeCajaBundle:ReintegroDeGastoDetalle')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a ReintegroDeGastoDetalle entity.
     *
     * @Route("/{id}/show", name="sistema_entidades_reintegrodegastodetalle_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:ReintegroDeGastoDetalle')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ReintegroDeGastoDetalle entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new ReintegroDeGastoDetalle entity.
     *
     * @Route("/new", name="sistema_entidades_reintegrodegastodetalle_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new ReintegroDeGastoDetalle();
        $form   = $this->createForm(new ReintegroDeGastoDetalleType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new ReintegroDeGastoDetalle entity.
     *
     * @Route("/create", name="sistema_entidades_reintegrodegastodetalle_create")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:ReintegroDeGastoDetalle:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new ReintegroDeGastoDetalle();
        $form = $this->createForm(new ReintegroDeGastoDetalleType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('sistema_entidades_reintegrodegastodetalle_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing ReintegroDeGastoDetalle entity.
     *
     * @Route("/{id}/edit", name="sistema_entidades_reintegrodegastodetalle_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:ReintegroDeGastoDetalle')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ReintegroDeGastoDetalle entity.');
        }

        $editForm = $this->createForm(new ReintegroDeGastoDetalleType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing ReintegroDeGastoDetalle entity.
     *
     * @Route("/{id}/update", name="sistema_entidades_reintegrodegastodetalle_update")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:ReintegroDeGastoDetalle:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:ReintegroDeGastoDetalle')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ReintegroDeGastoDetalle entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ReintegroDeGastoDetalleType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('sistema_entidades_reintegrodegastodetalle_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a ReintegroDeGastoDetalle entity.
     *
     * @Route("/{id}/delete", name="sistema_entidades_reintegrodegastodetalle_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MagypRendicionDeCajaBundle:ReintegroDeGastoDetalle')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ReintegroDeGastoDetalle entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sistema_entidades_reintegrodegastodetalle'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
