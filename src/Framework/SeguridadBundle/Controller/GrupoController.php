<?php

namespace Framework\SeguridadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Framework\SeguridadBundle\Entity\Grupo;
use Framework\SeguridadBundle\Form\GrupoType;

/**
 * Grupo controller.
 *
 * @Route("/admin/gestionusuario/grupo")
 */
class GrupoController extends Controller {

	/**
	 * Lists all Grupo entities.
	 *
	 * @Route("/", name="admin_gestionusuario_grupo")
	 * @Template()
	 */
	public function indexAction() {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('FrameworkSeguridadBundle:Grupo')->findAll();

		return array('entities' => $entities);
	}

	/**
	 * Finds and displays a Grupo entity.
	 *
	 * @Route("/{id}/show", name="admin_gestionusuario_grupo_show")
	 * @Template()
	 */
	public function showAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('FrameworkSeguridadBundle:Grupo')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Grupo entity.');
		}

		$deleteForm = $this->createDeleteForm($id);

		return array(
			'entity' => $entity,
			'delete_form' => $deleteForm->createView(),);
	}

	/**
	 * Displays a form to create a new Grupo entity.
	 *
	 * @Route("/new", name="admin_gestionusuario_grupo_new")
	 * @Template()
	 */
	public function newAction() {
		$entity = new Grupo();
		$form = $this->createForm(new GrupoType(), $entity);

		return array(
			'entity' => $entity,
			'form' => $form->createView()
		);
	}

	/**
	 * Creates a new Grupo entity.
	 *
	 * @Route("/create", name="admin_gestionusuario_grupo_create")
	 * @Method("post")
	 * @Template("FrameworkSeguridadBundle:Grupo:new.html.twig")
	 */
	public function createAction() {
		$entity = new Grupo();
		$request = $this->getRequest();
		$form = $this->createForm(new GrupoType(), $entity);
		$form->handleRequest($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			return $this->redirect($this->generateUrl('admin_gestionusuario_grupo_show', array('id' => $entity->getId())));
		}

		return array(
			'entity' => $entity,
			'form' => $form->createView()
		);
	}

	/**
	 * Displays a form to edit an existing Grupo entity.
	 *
	 * @Route("/{id}/edit", name="admin_gestionusuario_grupo_edit")
	 * @Template()
	 */
	public function editAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('FrameworkSeguridadBundle:Grupo')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Grupo entity.');
		}

		$editForm = $this->createForm(new GrupoType(), $entity);
		$deleteForm = $this->createDeleteForm($id);

		return array(
			'entity' => $entity,
			'edit_form' => $editForm->createView(),
			'delete_form' => $deleteForm->createView(),
		);
	}

	/**
	 * Edits an existing Grupo entity.
	 *
	 * @Route("/{id}/update", name="admin_gestionusuario_grupo_update")
	 * @Method("post")
	 * @Template("FrameworkSeguridadBundle:Grupo:edit.html.twig")
	 */
	public function updateAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('FrameworkSeguridadBundle:Grupo')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Grupo entity.');
		}

		$editForm = $this->createForm(new GrupoType(), $entity);
		$deleteForm = $this->createDeleteForm($id);

		$request = $this->getRequest();

		$editForm->handleRequest($request);

		if ($editForm->isValid()) {
			$em->persist($entity);
			$em->flush();

			return $this->redirect($this->generateUrl('admin_gestionusuario_grupo_edit', array('id' => $id)));
		}

		return array(
			'entity' => $entity,
			'edit_form' => $editForm->createView(),
			'delete_form' => $deleteForm->createView(),
		);
	}

	/**
	 * Deletes a Grupo entity.
	 *
	 * @Route("/{id}/delete", name="admin_gestionusuario_grupo_delete")
	 * @Method("post")
	 */
	public function deleteAction($id) {
		$form = $this->createDeleteForm($id);
		$request = $this->getRequest();

		$form->handleRequest($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('FrameworkSeguridadBundle:Grupo')->find($id);

			if (!$entity) {
				throw $this->createNotFoundException('Unable to find Grupo entity.');
			}

			$em->remove($entity);
			$em->flush();
		}

		return $this->redirect($this->generateUrl('admin_gestionusuario_grupo'));
	}

	private function createDeleteForm($id) {
		return $this->createFormBuilder(array('id' => $id))
						->add('id', 'hidden')
						->getForm()
		;
	}

}
