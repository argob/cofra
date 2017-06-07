<?php

namespace Magyp\RendicionDeCajaBundle\Controller\Entidades;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\Liquidacion;
use Magyp\RendicionDeCajaBundle\Form\LiquidacionType;
 
/**
 * Liquidacion controller.
 *
 * @Route("/liquidacion")
 */
class LiquidacionController extends Controller
{
    /**
     * Lists all Liquidacion entities.
     *
     * @Route("/", name="entidades_liquidacion")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MagypRendicionDeCajaBundle:Liquidacion')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Liquidacion entity.
     *
     * @Route("/{id}/show", name="entidades_liquidacion_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:Liquidacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Liquidacion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Liquidacion entity.
     *
     * @Route("/new", name="entidades_liquidacion_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Liquidacion();
        $form   = $this->createForm(new LiquidacionType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Liquidacion entity.
     *
     * @Route("/create", name="entidades_liquidacion_create")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:Liquidacion:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Liquidacion();
        $form = $this->createForm(new LiquidacionType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('entidades_liquidacion_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Liquidacion entity.
     *
     * @Route("/{id}/edit", name="entidades_liquidacion_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:Liquidacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Liquidacion entity.');
        }

        $editForm = $this->createForm(new LiquidacionType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Liquidacion entity.
     *
     * @Route("/{id}/update", name="entidades_liquidacion_update")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:Liquidacion:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:Liquidacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Liquidacion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new LiquidacionType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('entidades_liquidacion_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Liquidacion entity.
     *
     * @Route("/{id}/delete", name="entidades_liquidacion_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MagypRendicionDeCajaBundle:Liquidacion')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Liquidacion entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('entidades_liquidacion'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
