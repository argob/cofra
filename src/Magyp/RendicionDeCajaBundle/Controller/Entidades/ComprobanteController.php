<?php

namespace Magyp\RendicionDeCajaBundle\Controller\Entidades;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\Comprobante;
use Magyp\RendicionDeCajaBundle\Form\ComprobanteType;

/**
 * Comprobante controller.
 *
 * @Route("/comprobante")
 */
class ComprobanteController extends Controller
{
    /**
     * Lists all Comprobante entities.
     *
     * @Route("/", name="comprobante")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MagypRendicionDeCajaBundle:Comprobante')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Comprobante entity.
     *
     * @Route("/{id}/show", name="comprobante_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:Comprobante')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Comprobante entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Comprobante entity.
     *
     * @Route("/new", name="comprobante_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Comprobante();
        $form   = $this->createForm(new ComprobanteType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Comprobante entity.
     *
     * @Route("/create", name="comprobante_create")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:Entidades\Comprobante:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Comprobante();
        $form = $this->createForm(new ComprobanteType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('comprobante_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Comprobante entity.
     *
     * @Route("/{id}/edit", name="comprobante_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:Comprobante')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Comprobante entity.');
        }

        $editForm = $this->createForm(new ComprobanteType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Comprobante entity.
     *
     * @Route("/{id}/update", name="comprobante_update")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:Comprobante:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:Comprobante')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Comprobante entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ComprobanteType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('comprobante_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Comprobante entity.
     *
     * @Route("/{id}/delete", name="comprobante_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MagypRendicionDeCajaBundle:Comprobante')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Comprobante entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('comprobante'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    /**     
     * @Route("/datosdeprueba/{desde}/{hasta}", name="comprobante_datosdeprueba")
     * 
     */
    public function datosdepruebaAction($desde,$hasta)
    {	
		
		$em = $this->getDoctrine()->getManager();
		$imputaciones = $em->getRepository("MagypRendicionDeCajaBundle:Imputacion")->findAll();
		
		$proveedores = $em->getRepository("MagypRendicionDeCajaBundle:Proveedor")->findAll();
		
		$rendiciones = $em->getRepository("MagypRendicionDeCajaBundle:Rendicion")->findAll();
		
		$comprobantes = array();
		
		for($i=$desde;$i<$hasta;$i++){
			echo $i;
			$comprobante = new Comprobante();
			$aux=str_pad($i,5, "0", STR_PAD_LEFT);
			$comprobante->setDescripcion("Descripcion {$aux}");
			$comprobante->setEsEspecial(0);
			$comprobante->setImporte(rand(100, 1000));
			$comprobante->setNumero($i);
			//$comprobante->setNumeroFoja(500+$i);
						
			$comprobante->setImputacion($imputaciones[rand(0,count($imputaciones)-1)]);			
			$comprobante->setProveedor($proveedores[rand(0,count($proveedores)-1)]);
			$comprobante->setRendicion($rendiciones[rand(0,count($rendiciones)-1)]);			
			$comprobantes[] = $comprobante;
			$em->persist($comprobante);
		}
		
		$em->flush();
	
		return new \Symfony\Component\HttpFoundation\Response();
	}
}
