<?php

namespace Magyp\RendicionDeCajaBundle\Controller\Entidades;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\FondoRotatorioImputacion;
use Magyp\RendicionDeCajaBundle\Form\FondoRotatorioImputacionType;

/**
 * FondoRotatorioImputacion controller.
 *
 * @Route("/fondorotatorioimputacion")
 */
class FondoRotatorioImputacionController extends Controller
{
    /**
     * Lists all FondoRotatorioImputacion entities.
     *
     * @Route("/", name="entidades_fondorotatorioimputacion")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorioImputacion')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a FondoRotatorioImputacion entity.
     *
     * @Route("/{id}/show", name="entidades_fondorotatorioimputacion_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorioImputacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FondoRotatorioImputacion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new FondoRotatorioImputacion entity.
     *
     * @Route("/new", name="entidades_fondorotatorioimputacion_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new FondoRotatorioImputacion();
        $form   = $this->createForm(new FondoRotatorioImputacionType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new FondoRotatorioImputacion entity.
     *
     * @Route("/create", name="entidades_fondorotatorioimputacion_create")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:FondoRotatorioImputacion:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new FondoRotatorioImputacion();
        $form = $this->createForm(new FondoRotatorioImputacionType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('entidades_fondorotatorioimputacion_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing FondoRotatorioImputacion entity.
     *
     * @Route("/{id}/edit", name="entidades_fondorotatorioimputacion_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorioImputacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FondoRotatorioImputacion entity.');
        }

        $editForm = $this->createForm(new FondoRotatorioImputacionType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing FondoRotatorioImputacion entity.
     *
     * @Route("/{id}/update", name="entidades_fondorotatorioimputacion_update")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:FondoRotatorioImputacion:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorioImputacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FondoRotatorioImputacion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new FondoRotatorioImputacionType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('entidades_fondorotatorioimputacion_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a FondoRotatorioImputacion entity.
     *
     * @Route("/{id}/delete", name="entidades_fondorotatorioimputacion_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorioImputacion')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find FondoRotatorioImputacion entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('entidades_fondorotatorioimputacion'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
