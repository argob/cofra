<?php

namespace Framework\SeguridadBundle\Controller;

//Para el sistema de seguridad que usa @Secure
use JMS\SecurityExtraBundle\Annotation\Secure;
//Para poder validar el annotation @Method
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
//Para poder validar el annotation @Route
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
//Para poder validar el annotation @Template
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//Entidades con las que trabaja
use Framework\SeguridadBundle\Entity\Usuario;
//Tipos de datos con los que trabaja
use Framework\SeguridadBundle\Form\UsuarioType;

/**
 * Usuario controller.
 *
 * @Route("/admin/gestionusuario/usuario")
 */
class UsuarioController extends Controller {

	/**
	 * Lists all Usuario entities.
	 *
	 * @Route("/", name="admin_gestionusuario_usuario")
	 * @Template()
	 */
	public function indexAction() {
		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('FrameworkSeguridadBundle:Usuario')->findAll();

		return array('entities' => $entities);
	}

	/**
	 * Finds and displays a Usuario entity.
	 * @Route("/{id}/show", name="admin_gestionusuario_usuario_show")
	 * @Template()
	 */
	public function showAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('FrameworkSeguridadBundle:Usuario')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Usuario entity.');
		}

		$deleteForm = $this->createDeleteForm($id);

		return array(
			'entity' => $entity,
			'delete_form' => $deleteForm->createView(),);
	}

	/**
	 * Displays a form to create a new Usuario entity.
	 *
	 * @Secure(roles="ROLE_ADMIN_ALTA_USUARIO,ROLE_ADMIN")
	 * @Route("/new", name="admin_gestionusuario_usuario_new")
	 * @Template()
	 */
	public function newAction() {
		$entity = new Usuario();
		$form = $this->createForm(new UsuarioType(), $entity);

		return array(
			'entity' => $entity,
			'form' => $form->createView()
		);
	}

	/**
	 * Creates a new Usuario entity.
	 *
	 * @Route("/create", name="admin_gestionusuario_usuario_create")
	 * @Method("post")
	 * @Template("FrameworkSeguridadBundle:Usuario:new.html.twig")
	 */
	public function createAction() {

		//@todo: chequear que no se repitan nombres de usuario
		$usuario = new Usuario();
		$factory = $this->get('security.encoder_factory');
		$encoder = $factory->getEncoder($usuario);

		$request = $this->getRequest();
		$form = $this->createForm(new UsuarioType(), $usuario);
		$form->handleRequest($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$usuario->setPassword($encoder->encodePassword($usuario->getPassword(), $usuario->getSalt()));
			$em->persist($usuario);
			$em->flush();

			return $this->redirect($this->generateUrl('admin_gestionusuario_usuario_show', array('id' => $usuario->getId())));
		}

		return array(
			'entity' => $usuario,
			'form' => $form->createView()
		);
	}

	/**
	 * Displays a form to edit an existing Usuario entity.
	 *
	 * @Route("/{id}/edit", name="admin_gestionusuario_usuario_edit")
	 * @Template()
	 */
	public function editAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('FrameworkSeguridadBundle:Usuario')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Usuario entity.');
		}

		$editForm = $this->createForm(new UsuarioType(), $entity);
		$deleteForm = $this->createDeleteForm($id);

		return array(
			'entity' => $entity,
			'edit_form' => $editForm->createView(),
			'delete_form' => $deleteForm->createView(),
		);
	}

	/**
	 * Edits an existing Usuario entity.
	 *
	 * @Route("/{id}/update", name="admin_gestionusuario_usuario_update")
	 * @Method("post")
	 * @Template("FrameworkSeguridadBundle:Usuario:edit.html.twig")
	 */
	public function updateAction($id) {
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('FrameworkSeguridadBundle:Usuario')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Usuario entity.');
		}

		$editForm = $this->createForm(new UsuarioType(), $entity);
		$deleteForm = $this->createDeleteForm($id);

		$request = $this->getRequest();

		$editForm->handleRequest($request);

		if ($editForm->isValid()) {
			$factory = $this->get('security.encoder_factory');
			$encoder = $factory->getEncoder($entity);
			$entity->setPassword($encoder->encodePassword($entity->getPassword(), $entity->getSalt()));
			$em->persist($entity);
			$em->flush();
			return $this->redirect($this->generateUrl('admin_gestionusuario_usuario_show', array('id' => $entity->getId())));
			//return $this->redirect($this->generateUrl('admin_gestionusuario_usuario_edit', array('id' => $id)));
		}

		return array(
			'entity' => $entity,
			'edit_form' => $editForm->createView(),
			'delete_form' => $deleteForm->createView(),
		);
	}

	/**
	 * Deletes a Usuario entity.
	 *
	 * @Route("/{id}/delete", name="admin_gestionusuario_usuario_delete")
	 * @Method("post")
	 */
	public function deleteAction($id) {
		$form = $this->createDeleteForm($id);
		$request = $this->getRequest();

		$form->handleRequest($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('FrameworkSeguridadBundle:Usuario')->find($id);

			if (!$entity) {
				throw $this->createNotFoundException('Unable to find Usuario entity.');
			}

			$em->remove($entity);
			$em->flush();
		}

		return $this->redirect($this->generateUrl('admin_gestionusuario_usuario'));
	}

	private function createDeleteForm($id) {
		return $this->createFormBuilder(array('id' => $id))
						->add('id', 'hidden')
						->getForm()
		;
	}

}