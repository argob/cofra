<?php

namespace Magyp\RendicionDeCajaBundle\Controller\Entidades;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\RetencionTipo;
use Magyp\RendicionDeCajaBundle\Form\RetencionTipoType;

/**
 * RetencionTipo controller.
 *
 * @Route("/retenciontipo")
 */
class RetencionTipoController extends Controller
{
    /**
     * Lists all RetencionTipo entities.
     *
     * @Route("/", name="sistema_entidades_retenciontipo")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MagypRendicionDeCajaBundle:RetencionTipo')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a RetencionTipo entity.
     *
     * @Route("/{id}/show", name="sistema_entidades_retenciontipo_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:RetencionTipo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RetencionTipo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new RetencionTipo entity.
     *
     * @Route("/new", name="sistema_entidades_retenciontipo_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new RetencionTipo();
        $form   = $this->createForm(new RetencionTipoType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new RetencionTipo entity.
     *
     * @Route("/create", name="sistema_entidades_retenciontipo_create")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:RetencionTipo:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new RetencionTipo();
        $form = $this->createForm(new RetencionTipoType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('sistema_entidades_retenciontipo_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing RetencionTipo entity.
     *
     * @Route("/{id}/edit", name="sistema_entidades_retenciontipo_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:RetencionTipo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RetencionTipo entity.');
        }

        $editForm = $this->createForm(new RetencionTipoType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing RetencionTipo entity.
     *
     * @Route("/{id}/update", name="sistema_entidades_retenciontipo_update")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:RetencionTipo:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:RetencionTipo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find RetencionTipo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new RetencionTipoType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('sistema_entidades_retenciontipo_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a RetencionTipo entity.
     *
     * @Route("/{id}/delete", name="sistema_entidades_retenciontipo_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MagypRendicionDeCajaBundle:RetencionTipo')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find RetencionTipo entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sistema_entidades_retenciontipo'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
