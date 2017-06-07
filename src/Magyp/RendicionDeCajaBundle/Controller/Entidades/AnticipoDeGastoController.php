<?php

namespace Magyp\RendicionDeCajaBundle\Controller\Entidades;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\AnticipoDeGasto;
use Magyp\RendicionDeCajaBundle\Form\AnticipoDeGastoType;

/**
 * AnticipoDeGasto controller.
 *
 * @Route("/anticipodegasto")
 */
class AnticipoDeGastoController extends Controller
{
    /**
     * Lists all AnticipoDeGasto entities.
     *
     * @Route("/", name="anticipodegasto")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MagypRendicionDeCajaBundle:AnticipoDeGasto')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a AnticipoDeGasto entity.
     *
     * @Route("/{id}/show", name="anticipodegasto_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:AnticipoDeGasto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AnticipoDeGasto entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new AnticipoDeGasto entity.
     *
     * @Route("/new", name="anticipodegasto_new")
     * @Template()
     */
    public function newAction()
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
     * @Route("/create", name="anticipodegasto_create")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:AnticipoDeGasto:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new AnticipoDeGasto();
        $form = $this->createForm(new AnticipoDeGastoType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('anticipodegasto_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing AnticipoDeGasto entity.
     *
     * @Route("/{id}/edit", name="anticipodegasto_edit")
     * @Template()
     */
    public function editAction($id)
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
     * @Route("/{id}/update", name="anticipodegasto_update")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:AnticipoDeGasto:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:AnticipoDeGasto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AnticipoDeGasto entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new AnticipoDeGastoType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('anticipodegasto_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a AnticipoDeGasto entity.
     *
     * @Route("/{id}/delete", name="anticipodegasto_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MagypRendicionDeCajaBundle:AnticipoDeGasto')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find AnticipoDeGasto entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('anticipodegasto'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
