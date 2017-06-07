<?php

namespace Framework\SeguridadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Framework\SeguridadBundle\Entity\Funcionalidad;
use Framework\SeguridadBundle\Form\FuncionalidadType;

/**
 * Funcionalidad controller.
 *
 * @Route("/admin/gestionpermiso/funcionalidad")
 */
class FuncionalidadController extends Controller {

	/**
	 * Lists all Funcionalidad entities.
	 *
	 * @Route("/", name="admin_gestionpermiso_funcionalidad")
	 * @Template()
	 */
	public function indexAction() {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('FrameworkSeguridadBundle:Funcionalidad')->findAll();

		return array('entities' => $entities);
	}

	/**
	 * Finds and displays a Funcionalidad entity.
	 *
	 * @Route("/{id}/show", name="admin_gestionpermiso_funcionalidad_show")
	 * @Template()
	 */
	public function showAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('FrameworkSeguridadBundle:Funcionalidad')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Funcionalidad entity.');
		}

		$deleteForm = $this->createDeleteForm($id);

		return array(
			'entity' => $entity,
			'delete_form' => $deleteForm->createView(),
			'acciones' => $entity->getAcciones(),
		);
	}

	/**
	 * Displays a form to create a new Funcionalidad entity.
	 *
	 * @Route("/new", name="admin_gestionpermiso_funcionalidad_new")
	 * @Template()
	 */
	public function newAction() {
		$entity = new Funcionalidad();
		$form = $this->createForm(new FuncionalidadType(), $entity);

		return array(
			'entity' => $entity,
			'form' => $form->createView()
		);
	}

	/**
	 * Creates a new Funcionalidad entity.
	 *
	 * @Route("/create", name="admin_gestionpermiso_funcionalidad_create")
	 * @Method("post")
	 * @Template("FrameworkSeguridadBundle:Funcionalidad:new.html.twig")
	 */
	public function createAction() {
		$entity = new Funcionalidad();
		$request = $this->getRequest();
		$form = $this->createForm(new FuncionalidadType(), $entity);
		$form->handleRequest($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			/* foreach($entity->getPermisosContenidos() as $func){
			  $func->addFuncionalidadPertenece($entity);
			  } */
			$em->persist($entity);
			$em->flush();

			return $this->redirect($this->generateUrl('admin_gestionpermiso_funcionalidad_show', array('id' => $entity->getId())));
		}

		return array(
			'entity' => $entity,
			'form' => $form->createView()
		);
	}

	/**
	 * Displays a form to edit an existing Funcionalidad entity.
	 *
	 * @Route("/{id}/edit", name="admin_gestionpermiso_funcionalidad_edit")
	 * @Template()
	 */
	public function editAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('FrameworkSeguridadBundle:Funcionalidad')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Funcionalidad entity.');
		}

		$editForm = $this->createForm(new FuncionalidadType(), $entity);
		$deleteForm = $this->createDeleteForm($id);

		return array(
			'entity' => $entity,
			'edit_form' => $editForm->createView(),
			'delete_form' => $deleteForm->createView(),
		);
	}

	/**
	 * Edits an existing Funcionalidad entity.
	 *
	 * @Route("/{id}/update", name="admin_gestionpermiso_funcionalidad_update")
	 * @Method("post")
	 * @Template("FrameworkSeguridadBundle:Funcionalidad:edit.html.twig")
	 */
	public function updateAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('FrameworkSeguridadBundle:Funcionalidad')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Funcionalidad entity.');
		}

		$editForm = $this->createForm(new FuncionalidadType(), $entity);
		$deleteForm = $this->createDeleteForm($id);

		$request = $this->getRequest();

		$editForm->handleRequest($request);

		if ($editForm->isValid()) {
			$em->persist($entity);
			$em->flush();

			return $this->redirect($this->generateUrl('admin_gestionpermiso_funcionalidad_show', array('id' => $id)));
		}

		return array(
			'entity' => $entity,
			'edit_form' => $editForm->createView(),
			'delete_form' => $deleteForm->createView(),
		);
	}

	/**
	 * Deletes a Funcionalidad entity.
	 *
	 * @Route("/{id}/delete", name="admin_gestionpermiso_funcionalidad_delete")
	 * @Method("post")
	 */
	public function deleteAction($id) {
		$form = $this->createDeleteForm($id);
		$request = $this->getRequest();

		$form->handleRequest($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('FrameworkSeguridadBundle:Funcionalidad')->find($id);

			if (!$entity) {
				throw $this->createNotFoundException('Unable to find Funcionalidad entity.');
			}

			$em->remove($entity);
			$em->flush();
		}

		return $this->redirect($this->generateUrl('admin_gestionpermiso_funcionalidad'));
	}

	private function createDeleteForm($id) {
		return $this->createFormBuilder(array('id' => $id))
						->add('id', 'hidden')
						->getForm()
		;
	}

}
