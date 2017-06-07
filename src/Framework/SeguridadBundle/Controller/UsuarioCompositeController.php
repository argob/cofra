<?php

namespace Framework\SeguridadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContext;
use Framework\SeguridadBundle\Entity\Grupo;
use Framework\SeguridadBundle\Entity\UsuarioComposite;
use Framework\SeguridadBundle\Form\PermisosType;

/**
 * Usuario controller.
 *
 * @Route("/admin/gestionusuario")
 */
class UsuarioCompositeController extends Controller {

	/**
	 * @Route("/{id}/permisos",name="admin_gestionusuario_editarpermisos")
	 * @Template()
	 */
	public function editarPermisosAction($id) {
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('FrameworkSeguridadBundle:UsuarioComposite')->find($id);

		$editPermisosForm = $this->createForm(new PermisosType(), $entity);
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Usuario entity.');
		}
		return array(
			'entity' => $entity,
			'editPermisos_Form' => $editPermisosForm->createView(),
		);
	}

	/**
	 * Agrega permisos a un usuario
	 *
	 * @Route("/{id}/modificarpermisos", name="admin_gestionusuario_modificarpermisos")
	 * @Method("post")
	 * @Template("FrameworkSeguridadBundle:Usuario:editarPermisos.html.twig")
	 */
	public function modificarPermisosAction($id) {
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('FrameworkSeguridadBundle:UsuarioComposite')->find($id);
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Usuario o Grupo entity.');
		}
		$editPermisosForm = $this->createForm(new PermisosType(), $entity);
		$request = $this->getRequest();
		$editPermisosForm->handleRequest($request);

		if ($editPermisosForm->isValid()) {
			$em->persist($entity);
			$em->flush();

			return $this->redirect($this->generateUrl('admin_gestionusuario_editarpermisos', array('id' => $id)));
		}

		return array(
			'entity' => $entity,
			'editPermisos_Form' => $editPermisosForm->createView(),
		);
	}

	/**
	 * @Route("/{id}/gruposPertenece",name="admin_gestionusuario_editargrupospertenece")
	 * @Template()
	 */
	public function editarGruposPerteneceAction($id) {
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('FrameworkSeguridadBundle:UsuarioComposite')->find($id);
		$choices = $em->getRepository('FrameworkSeguridadBundle:Grupo')->findAll();
		/* @var $entity UsuarioComposite */
		if ($entity->getTipoUC() == UsuarioComposite::TIPO_GRUPO) {
			//estaria bueno poder hacer una consulta
			$choicesNO = $entity->getContenidosRecursivo();
			$choicesNO[] = $entity;
			$choices = array_diff($choices, $choicesNO);
		}
		$editGruposForm = $this->createFormBuilder($entity)->add("gruposPertenece"
						, "entity", array(
					'class' => 'FrameworkSeguridadBundle:Grupo',
					'choices' => $choices,
					'multiple' => true,
					'expanded' => true,
					'label' => 'Grupos pertenece'
						)
				)->getForm();
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Usuario entity.');
		}
		return array(
			'entity' => $entity,
			'editGrupos_Form' => $editGruposForm->createView(),
		);
	}

	/**
	 * Agrega permisos a un usuario
	 *
	 * @Route("/{id}/modificargrupos", name="admin_gestionusuario_modificargrupos")
	 * @Method("post")
	 * @Template("FrameworkSeguridadBundle:Usuario:editarGruposPertenece.html.twig")
	 */
	public function modificarGruposAction($id) {
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('FrameworkSeguridadBundle:UsuarioComposite')->find($id);
		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Usuario o Grupo entity.');
		}
		$editGruposForm = $this->createFormBuilder($entity)->add("gruposPertenece")->getForm();
		$request = $this->getRequest();
		$editGruposForm->handleRequest($request);

		if ($editGruposForm->isValid()) {
			$em->persist($entity);
			$em->flush();

			return $this->redirect($this->generateUrl('admin_gestionusuario_editargrupospertenece', array('id' => $id)));
		}

		return array(
			'entity' => $entity,
			'editGrupos_Form' => $editGruposForm->createView(),
		);
	}

}

?>