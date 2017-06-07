<?php

namespace Framework\SeguridadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Framework\SeguridadBundle\Entity\Accion;
use Framework\SeguridadBundle\Form\AccionType;

/**
 * Accion controller.
 *
 * @Route("/admin/gestionpermiso/accion")
 */
class AccionController extends Controller {

	/**
	 * Lists all Accion entities.
	 *
	 * @Route("/", name="admin_gestionpermiso_accion")
	 * @Template()
	 */
	public function indexAction() {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('FrameworkSeguridadBundle:Accion')->findAll();

		return array('entities' => $entities);
	}

	/**
	 * Finds and displays a Accion entity.
	 *
	 * @Route("/{id}/show", name="admin_gestionpermiso_accion_show")
	 * @Template()
	 */
	public function showAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('FrameworkSeguridadBundle:Accion')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Accion entity.');
		}

		$deleteForm = $this->createDeleteForm($id);

		return array(
			'entity' => $entity,
			'delete_form' => $deleteForm->createView(),);
	}

	/**
	 * Displays a form to create a new Accion entity.
	 *
	 * @Route("/new", name="admin_gestionpermiso_accion_new")
	 * @Template()
	 */
	public function newAction() {
		$entity = new Accion();
		$form = $this->createForm(new AccionType(), $entity);

		return array(
			'entity' => $entity,
			'form' => $form->createView()
		);
	}

	/**
	 * Creates a new Accion entity.
	 *
	 * @Route("/create", name="admin_gestionpermiso_accion_create")
	 * @Method("post")
	 * @Template("FrameworkSeguridadBundle:Accion:new.html.twig")
	 */
	public function createAction() {
		$entity = new Accion();
		$request = $this->getRequest();
		$form = $this->createForm(new AccionType(), $entity);
		$form->handleRequest($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			return $this->redirect($this->generateUrl('admin_gestionpermiso_accion_show', array('id' => $entity->getId())));
		}

		return array(
			'entity' => $entity,
			'form' => $form->createView()
		);
	}

	/**
	 * Displays a form to edit an existing Accion entity.
	 *
	 * @Route("/{id}/edit", name="admin_gestionpermiso_accion_edit")
	 * @Template()
	 */
	public function editAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('FrameworkSeguridadBundle:Accion')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Accion entity.');
		}

		$editForm = $this->createForm(new AccionType(), $entity);
		$deleteForm = $this->createDeleteForm($id);

		return array(
			'entity' => $entity,
			'edit_form' => $editForm->createView(),
			'delete_form' => $deleteForm->createView(),
		);
	}

	/**
	 * Edits an existing Accion entity.
	 *
	 * @Route("/{id}/update", name="admin_gestionpermiso_accion_update")
	 * @Method("post")
	 * @Template("FrameworkSeguridadBundle:Accion:edit.html.twig")
	 */
	public function updateAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('FrameworkSeguridadBundle:Accion')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Accion entity.');
		}

		$editForm = $this->createForm(new AccionType(), $entity);
		$deleteForm = $this->createDeleteForm($id);

		$request = $this->getRequest();

		$editForm->handleRequest($request);

		if ($editForm->isValid()) {
			$em->persist($entity);
			$em->flush();

			return $this->redirect($this->generateUrl('admin_gestionpermiso_accion_edit', array('id' => $id)));
		}

		return array(
			'entity' => $entity,
			'edit_form' => $editForm->createView(),
			'delete_form' => $deleteForm->createView(),
		);
	}

	/**
	 * Deletes a Accion entity.
	 *
	 * @Route("/{id}/delete", name="admin_gestionpermiso_accion_delete")
	 * @Method("post")
	 */
	public function deleteAction($id) {
		$form = $this->createDeleteForm($id);
		$request = $this->getRequest();

		$form->handleRequest($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('FrameworkSeguridadBundle:Accion')->find($id);

			if (!$entity) {
				throw $this->createNotFoundException('Unable to find Accion entity.');
			}

			$em->remove($entity);
			$em->flush();
		}

		return $this->redirect($this->generateUrl('admin_gestionpermiso_accion'));
	}

	private function createDeleteForm($id) {
		return $this->createFormBuilder(array('id' => $id))
						->add('id', 'hidden')
						->getForm()
		;
	}

}
