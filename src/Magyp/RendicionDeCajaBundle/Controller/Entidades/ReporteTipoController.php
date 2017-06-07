<?php

namespace Magyp\RendicionDeCajaBundle\Controller\Entidades;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\ReporteTipo;
use Magyp\RendicionDeCajaBundle\Form\ReporteTipoType;

/**
 * ReporteTipo controller.
 *
 * @Route("/reportetipo")
 */
class ReporteTipoController extends Controller
{
    /**
     * Lists all ReporteTipo entities.
     *
     * @Route("/", name="entidades_reportetipo")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MagypRendicionDeCajaBundle:ReporteTipo')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a ReporteTipo entity.
     *
     * @Route("/{id}/show", name="entidades_reportetipo_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:ReporteTipo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ReporteTipo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new ReporteTipo entity.
     *
     * @Route("/new", name="entidades_reportetipo_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new ReporteTipo();
        $form   = $this->createForm(new ReporteTipoType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new ReporteTipo entity.
     *
     * @Route("/create", name="entidades_reportetipo_create")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:ReporteTipo:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new ReporteTipo();
        $form = $this->createForm(new ReporteTipoType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('entidades_reportetipo_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing ReporteTipo entity.
     *
     * @Route("/{id}/edit", name="entidades_reportetipo_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:ReporteTipo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ReporteTipo entity.');
        }

        $editForm = $this->createForm(new ReporteTipoType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing ReporteTipo entity.
     *
     * @Route("/{id}/update", name="entidades_reportetipo_update")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:ReporteTipo:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:ReporteTipo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ReporteTipo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ReporteTipoType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('entidades_reportetipo_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a ReporteTipo entity.
     *
     * @Route("/{id}/delete", name="entidades_reportetipo_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MagypRendicionDeCajaBundle:ReporteTipo')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ReporteTipo entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('entidades_reportetipo'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
