<?php

namespace Magyp\RendicionDeCajaBundle\Controller\Entidades;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\Abm;
use Magyp\RendicionDeCajaBundle\Form\AbmType;

/**
 * Abm controller.
 *
 * @Route("/abm")
 */
class AbmController extends Controller
{
    /**
     * Lists all Abm entities.
     *
     * @Route("/", name="abm")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MagypRendicionDeCajaBundle:Abm')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Abm entity.
     *
     * @Route("/{id}/show", name="abm_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:Abm')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Abm entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Abm entity.
     *
     * @Route("/new", name="abm_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Abm();
        $form   = $this->createForm(new AbmType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Abm entity.
     *
     * @Route("/create", name="abm_create")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:Abm:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Abm();
        $form = $this->createForm(new AbmType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('abm_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Abm entity.
     *
     * @Route("/{id}/edit", name="abm_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:Abm')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Abm entity.');
        }

        $editForm = $this->createForm(new AbmType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Abm entity.
     *
     * @Route("/{id}/update", name="abm_update")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:Abm:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:Abm')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Abm entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new AbmType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('abm_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Abm entity.
     *
     * @Route("/{id}/delete", name="abm_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MagypRendicionDeCajaBundle:Abm')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Abm entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('abm'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
