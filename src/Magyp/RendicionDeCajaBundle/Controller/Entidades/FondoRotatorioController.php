<?php

namespace Magyp\RendicionDeCajaBundle\Controller\Entidades;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\FondoRotatorio;
use Magyp\RendicionDeCajaBundle\Form\FondoRotatorioType;

/**
 * FondoRotatorio controller.
 *
 * @Route("/fondorotatorio/")
 */
class FondoRotatorioController extends Controller
{
    /**
     * Lists all FondoRotatorio entities.
     *
     * @Route("/", name="entidades_fondorotatorio")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorio')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a FondoRotatorio entity.
     *
     * @Route("/{id}/show", name="entidades_fondorotatorio_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FondoRotatorio entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new FondoRotatorio entity.
     *
     * @Route("/new", name="entidades_fondorotatorio_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new FondoRotatorio();
        $form   = $this->createForm(new FondoRotatorioType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new FondoRotatorio entity.
     *
     * @Route("/create", name="entidades_fondorotatorio_create")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:FondoRotatorio:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new FondoRotatorio();
        $form = $this->createForm(new FondoRotatorioType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('entidades_fondorotatorio_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing FondoRotatorio entity.
     *
     * @Route("/{id}/edit", name="entidades_fondorotatorio_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FondoRotatorio entity.');
        }

        $editForm = $this->createForm(new FondoRotatorioType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing FondoRotatorio entity.
     *
     * @Route("/{id}/update", name="entidades_fondorotatorio_update")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:FondoRotatorio:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FondoRotatorio entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new FondoRotatorioType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('entidades_fondorotatorio_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a FondoRotatorio entity.
     *
     * @Route("/{id}/delete", name="entidades_fondorotatorio_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorio')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find FondoRotatorio entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('entidades_fondorotatorio'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
