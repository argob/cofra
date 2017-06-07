<?php

namespace Magyp\RendicionDeCajaBundle\Controller\Entidades;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\FondoRotatorioRetencion;
use Magyp\RendicionDeCajaBundle\Form\FondoRotatorioRetencionType;

/**
 * FondoRotatorioRetencion controller.
 *
 * @Route("/fondorotatorioretencion")
 */
class FondoRotatorioRetencionController extends Controller
{
    /**
     * Lists all FondoRotatorioRetencion entities.
     *
     * @Route("/", name="entidades_fondorotatorioretencion")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorioRetencion')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a FondoRotatorioRetencion entity.
     *
     * @Route("/{id}/show", name="entidades_fondorotatorioretencion_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorioRetencion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FondoRotatorioRetencion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new FondoRotatorioRetencion entity.
     *
     * @Route("/new", name="entidades_fondorotatorioretencion_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new FondoRotatorioRetencion();
        $form   = $this->createForm(new FondoRotatorioRetencionType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new FondoRotatorioRetencion entity.
     *
     * @Route("/create", name="entidades_fondorotatorioretencion_create")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:FondoRotatorioRetencion:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new FondoRotatorioRetencion();
        $form = $this->createForm(new FondoRotatorioRetencionType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('entidades_fondorotatorioretencion_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing FondoRotatorioRetencion entity.
     *
     * @Route("/{id}/edit", name="entidades_fondorotatorioretencion_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorioRetencion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FondoRotatorioRetencion entity.');
        }

        $editForm = $this->createForm(new FondoRotatorioRetencionType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing FondoRotatorioRetencion entity.
     *
     * @Route("/{id}/update", name="entidades_fondorotatorioretencion_update")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:FondoRotatorioRetencion:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorioRetencion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FondoRotatorioRetencion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new FondoRotatorioRetencionType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('entidades_fondorotatorioretencion_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a FondoRotatorioRetencion entity.
     *
     * @Route("/{id}/delete", name="entidades_fondorotatorioretencion_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorioRetencion')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find FondoRotatorioRetencion entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('entidades_fondorotatorioretencion'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
