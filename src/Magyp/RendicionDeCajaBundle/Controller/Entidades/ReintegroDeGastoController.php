<?php

namespace Magyp\RendicionDeCajaBundle\Controller\Entidades;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\ReintegroDeGasto;
use Magyp\RendicionDeCajaBundle\Form\ReintegroDeGastoType;

/**
 * ReintegroDeGasto controller.
 *
 * @Route("/reintegrodegasto")
 */
class ReintegroDeGastoController extends Controller
{
    /**
     * Lists all ReintegroDeGasto entities.
     *
     * @Route("/", name="sistema_entidades_reintegrodegasto")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MagypRendicionDeCajaBundle:ReintegroDeGasto')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a ReintegroDeGasto entity.
     *
     * @Route("/{id}/show", name="sistema_entidades_reintegrodegasto_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:ReintegroDeGasto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ReintegroDeGasto entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new ReintegroDeGasto entity.
     *
     * @Route("/new", name="sistema_entidades_reintegrodegasto_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new ReintegroDeGasto();
        $form   = $this->createForm(new ReintegroDeGastoType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new ReintegroDeGasto entity.
     *
     * @Route("/create", name="sistema_entidades_reintegrodegasto_create")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:ReintegroDeGasto:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new ReintegroDeGasto();
        $form = $this->createForm(new ReintegroDeGastoType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('sistema_entidades_reintegrodegasto_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing ReintegroDeGasto entity.
     *
     * @Route("/{id}/edit", name="sistema_entidades_reintegrodegasto_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:ReintegroDeGasto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ReintegroDeGasto entity.');
        }

        $editForm = $this->createForm(new ReintegroDeGastoType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing ReintegroDeGasto entity.
     *
     * @Route("/{id}/update", name="sistema_entidades_reintegrodegasto_update")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:ReintegroDeGasto:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:ReintegroDeGasto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ReintegroDeGasto entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ReintegroDeGastoType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('sistema_entidades_reintegrodegasto_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a ReintegroDeGasto entity.
     *
     * @Route("/{id}/delete", name="sistema_entidades_reintegrodegasto_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MagypRendicionDeCajaBundle:ReintegroDeGasto')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ReintegroDeGasto entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sistema_entidades_reintegrodegasto'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
