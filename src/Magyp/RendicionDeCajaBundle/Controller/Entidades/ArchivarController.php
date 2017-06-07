<?php

namespace Magyp\RendicionDeCajaBundle\Controller\Entidades;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\Archivar;
use Magyp\RendicionDeCajaBundle\Form\ArchivarType;

/**
 * Archivar controller.
 *
 * @Route("/archivar")
 */
class ArchivarController extends Controller
{
    /**
     * Lists all Archivar entities.
     *
     * @Route("/", name="entidades_archivar")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MagypRendicionDeCajaBundle:Archivar')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Archivar entity.
     *
     * @Route("/{id}/show", name="entidades_archivar_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:Archivar')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Archivar entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Archivar entity.
     *
     * @Route("/new", name="entidades_archivar_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Archivar();
        $form   = $this->createForm(new ArchivarType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Archivar entity.
     *
     * @Route("/create", name="entidades_archivar_create")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:Archivar:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Archivar();
        $form = $this->createForm(new ArchivarType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('entidades_archivar_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Archivar entity.
     *
     * @Route("/{id}/edit", name="entidades_archivar_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:Archivar')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Archivar entity.');
        }

        $editForm = $this->createForm(new ArchivarType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Archivar entity.
     *
     * @Route("/{id}/update", name="entidades_archivar_update")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:Archivar:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:Archivar')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Archivar entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ArchivarType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('entidades_archivar_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Archivar entity.
     *
     * @Route("/{id}/delete", name="entidades_archivar_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MagypRendicionDeCajaBundle:Archivar')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Archivar entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('entidades_archivar'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
