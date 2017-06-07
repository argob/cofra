<?php

namespace Magyp\RendicionDeCajaBundle\Controller\Entidades;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\FuenteFinanciamiento;
use Magyp\RendicionDeCajaBundle\Form\FuenteFinanciamientoType;

/**
 * FuenteFinanciamiento controller.
 *
 * @Route("/fuentefinanciamiento")
 */
class FuenteFinanciamientoController extends Controller
{
    /**
     * Lists all FuenteFinanciamiento entities.
     *
     * @Route("/", name="entidades_fuentefinanciamiento")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MagypRendicionDeCajaBundle:FuenteFinanciamiento')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a FuenteFinanciamiento entity.
     *
     * @Route("/{id}/show", name="entidades_fuentefinanciamiento_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:FuenteFinanciamiento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FuenteFinanciamiento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new FuenteFinanciamiento entity.
     *
     * @Route("/new", name="entidades_fuentefinanciamiento_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new FuenteFinanciamiento();
        $form   = $this->createForm(new FuenteFinanciamientoType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new FuenteFinanciamiento entity.
     *
     * @Route("/create", name="entidades_fuentefinanciamiento_create")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:FuenteFinanciamiento:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new FuenteFinanciamiento();
        $form = $this->createForm(new FuenteFinanciamientoType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('entidades_fuentefinanciamiento_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing FuenteFinanciamiento entity.
     *
     * @Route("/{id}/edit", name="entidades_fuentefinanciamiento_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:FuenteFinanciamiento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FuenteFinanciamiento entity.');
        }

        $editForm = $this->createForm(new FuenteFinanciamientoType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing FuenteFinanciamiento entity.
     *
     * @Route("/{id}/update", name="entidades_fuentefinanciamiento_update")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:FuenteFinanciamiento:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:FuenteFinanciamiento')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FuenteFinanciamiento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new FuenteFinanciamientoType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('entidades_fuentefinanciamiento_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a FuenteFinanciamiento entity.
     *
     * @Route("/{id}/delete", name="entidades_fuentefinanciamiento_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MagypRendicionDeCajaBundle:FuenteFinanciamiento')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find FuenteFinanciamiento entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('entidades_fuentefinanciamiento'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
