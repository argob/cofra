<?php

namespace Magyp\RendicionDeCajaBundle\Controller\Entidades;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\FondoRotatorioFactura;
use Magyp\RendicionDeCajaBundle\Form\FondoRotatorioFacturaType;

/**
 * FondoRotatorioFactura controller.
 *
 * @Route("/fondorotatoriofactura")
 */
class FondoRotatorioFacturaController extends Controller
{
    /**
     * Lists all FondoRotatorioFactura entities.
     *
     * @Route("/", name="entidades_fondorotatoriofactura")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorioFactura')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a FondoRotatorioFactura entity.
     *
     * @Route("/{id}/show", name="entidades_fondorotatoriofactura_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorioFactura')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FondoRotatorioFactura entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new FondoRotatorioFactura entity.
     *
     * @Route("/new", name="entidades_fondorotatoriofactura_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new FondoRotatorioFactura();
        $form   = $this->createForm(new FondoRotatorioFacturaType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new FondoRotatorioFactura entity.
     *
     * @Route("/create", name="entidades_fondorotatoriofactura_create")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:FondoRotatorioFactura:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new FondoRotatorioFactura();
        $form = $this->createForm(new FondoRotatorioFacturaType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('entidades_fondorotatoriofactura_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing FondoRotatorioFactura entity.
     *
     * @Route("/{id}/edit", name="entidades_fondorotatoriofactura_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorioFactura')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FondoRotatorioFactura entity.');
        }

        $editForm = $this->createForm(new FondoRotatorioFacturaType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing FondoRotatorioFactura entity.
     *
     * @Route("/{id}/update", name="entidades_fondorotatoriofactura_update")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:FondoRotatorioFactura:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorioFactura')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FondoRotatorioFactura entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new FondoRotatorioFacturaType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('entidades_fondorotatoriofactura_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a FondoRotatorioFactura entity.
     *
     * @Route("/{id}/delete", name="entidades_fondorotatoriofactura_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MagypRendicionDeCajaBundle:FondoRotatorioFactura')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find FondoRotatorioFactura entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('entidades_fondorotatoriofactura'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
