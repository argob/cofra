<?php

namespace Magyp\RendicionDeCajaBundle\Controller\Entidades;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\Leyenda;
use Magyp\RendicionDeCajaBundle\Form\LeyendaType;

/**
 * Leyenda controller.
 *
 * @Route("/leyenda")
 */
class LeyendaController extends Controller
{
    /**
     * Lists all Leyenda entities.
     *
     * @Route("/", name="entidades_leyenda")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MagypRendicionDeCajaBundle:Leyenda')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Leyenda entity.
     *
     * @Route("/{id}/show", name="entidades_leyenda_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:Leyenda')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Leyenda entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Leyenda entity.
     *
     * @Route("/new", name="entidades_leyenda_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Leyenda();
        $form   = $this->createForm(new LeyendaType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Leyenda entity.
     *
     * @Route("/create", name="entidades_leyenda_create")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:Leyenda:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Leyenda();
        $form = $this->createForm(new LeyendaType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('entidades_leyenda_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Leyenda entity.
     *
     * @Route("/{id}/edit", name="entidades_leyenda_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:Leyenda')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Leyenda entity.');
        }

        $editForm = $this->createForm(new LeyendaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Leyenda entity.
     *
     * @Route("/{id}/update", name="entidades_leyenda_update")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:Leyenda:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:Leyenda')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Leyenda entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new LeyendaType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('entidades_leyenda_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Leyenda entity.
     *
     * @Route("/{id}/delete", name="entidades_leyenda_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MagypRendicionDeCajaBundle:Leyenda')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Leyenda entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('entidades_leyenda'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
