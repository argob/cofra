<?php

namespace Magyp\RendicionDeCajaBundle\Controller\Entidades;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\LiquidacionDetalle;
use Magyp\RendicionDeCajaBundle\Form\LiquidacionDetalleType;

/**
 * LiquidacionDetalle controller.
 *
 * @Route("/liquidaciondetalle")
 */
class LiquidacionDetalleController extends Controller
{
    /**
     * Lists all LiquidacionDetalle entities.
     *
     * @Route("/", name="entidades_liquidaciondetalle")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MagypRendicionDeCajaBundle:LiquidacionDetalle')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a LiquidacionDetalle entity.
     *
     * @Route("/{id}/show", name="entidades_liquidaciondetalle_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:LiquidacionDetalle')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find LiquidacionDetalle entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new LiquidacionDetalle entity.
     *
     * @Route("/new", name="entidades_liquidaciondetalle_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new LiquidacionDetalle();
        $form   = $this->createForm(new LiquidacionDetalleType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new LiquidacionDetalle entity.
     *
     * @Route("/create", name="entidades_liquidaciondetalle_create")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:LiquidacionDetalle:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new LiquidacionDetalle();
        $form = $this->createForm(new LiquidacionDetalleType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('entidades_liquidaciondetalle_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing LiquidacionDetalle entity.
     *
     * @Route("/{id}/edit", name="entidades_liquidaciondetalle_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:LiquidacionDetalle')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find LiquidacionDetalle entity.');
        }

        $editForm = $this->createForm(new LiquidacionDetalleType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing LiquidacionDetalle entity.
     *
     * @Route("/{id}/update", name="entidades_liquidaciondetalle_update")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:LiquidacionDetalle:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:LiquidacionDetalle')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find LiquidacionDetalle entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new LiquidacionDetalleType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('entidades_liquidaciondetalle_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a LiquidacionDetalle entity.
     *
     * @Route("/{id}/delete", name="entidades_liquidaciondetalle_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MagypRendicionDeCajaBundle:LiquidacionDetalle')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find LiquidacionDetalle entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('entidades_liquidaciondetalle'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
