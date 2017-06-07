<?php

namespace Magyp\RendicionDeCajaBundle\Controller\Entidades;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\UbicacionGeografica;
use Magyp\RendicionDeCajaBundle\Form\UbicacionGeograficaType;

/**
 * UbicacionGeografica controller.
 *
 * @Route("/ubicaciongeografica")
 */
class UbicacionGeograficaController extends Controller
{
    /**
     * Lists all UbicacionGeografica entities.
     *
     * @Route("/", name="entidades_ubicaciongeografica")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MagypRendicionDeCajaBundle:UbicacionGeografica')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a UbicacionGeografica entity.
     *
     * @Route("/{id}/show", name="entidades_ubicaciongeografica_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:UbicacionGeografica')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UbicacionGeografica entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new UbicacionGeografica entity.
     *
     * @Route("/new", name="entidades_ubicaciongeografica_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new UbicacionGeografica();
        $form   = $this->createForm(new UbicacionGeograficaType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new UbicacionGeografica entity.
     *
     * @Route("/create", name="entidades_ubicaciongeografica_create")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:UbicacionGeografica:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new UbicacionGeografica();
        $form = $this->createForm(new UbicacionGeograficaType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('entidades_ubicaciongeografica_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing UbicacionGeografica entity.
     *
     * @Route("/{id}/edit", name="entidades_ubicaciongeografica_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:UbicacionGeografica')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UbicacionGeografica entity.');
        }

        $editForm = $this->createForm(new UbicacionGeograficaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing UbicacionGeografica entity.
     *
     * @Route("/{id}/update", name="entidades_ubicaciongeografica_update")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:UbicacionGeografica:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:UbicacionGeografica')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UbicacionGeografica entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new UbicacionGeograficaType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('entidades_ubicaciongeografica_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a UbicacionGeografica entity.
     *
     * @Route("/{id}/delete", name="entidades_ubicaciongeografica_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MagypRendicionDeCajaBundle:UbicacionGeografica')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find UbicacionGeografica entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('entidades_ubicaciongeografica'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
