<?php

namespace Magyp\RendicionDeCajaBundle\Controller\Entidades;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\ImputacionTipo;
use Magyp\RendicionDeCajaBundle\Form\ImputacionTipoType;

/**
 * ImputacionTipo controller.
 *
 * @Route("/imputaciontipo")
 */
class ImputacionTipoController extends Controller
{
    /**
     * Lists all ImputacionTipo entities.
     *
     * @Route("/", name="imputaciontipo")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MagypRendicionDeCajaBundle:ImputacionTipo')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a ImputacionTipo entity.
     *
     * @Route("/{id}/show", name="imputaciontipo_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:ImputacionTipo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ImputacionTipo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new ImputacionTipo entity.
     *
     * @Route("/new", name="imputaciontipo_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new ImputacionTipo();
        $form   = $this->createForm(new ImputacionTipoType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new ImputacionTipo entity.
     *
     * @Route("/create", name="imputaciontipo_create")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:ImputacionTipo:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new ImputacionTipo();
        $form = $this->createForm(new ImputacionTipoType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('imputaciontipo_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing ImputacionTipo entity.
     *
     * @Route("/{id}/edit", name="imputaciontipo_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:ImputacionTipo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ImputacionTipo entity.');
        }

        $editForm = $this->createForm(new ImputacionTipoType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing ImputacionTipo entity.
     *
     * @Route("/{id}/update", name="imputaciontipo_update")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:ImputacionTipo:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:ImputacionTipo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ImputacionTipo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ImputacionTipoType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('imputaciontipo_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a ImputacionTipo entity.
     *
     * @Route("/{id}/delete", name="imputaciontipo_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MagypRendicionDeCajaBundle:ImputacionTipo')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ImputacionTipo entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('imputaciontipo'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
