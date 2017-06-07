<?php

namespace Framework\SeguridadBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Description of AdminController
 * @Route("/admin")
 */
class AdminController extends Controller {

	/**
	 * @Route("/", name="admin")
	 * @Template("FrameworkSeguridadBundle:Admin:opcionizador.html.twig")
	 */
	public function indexAction() {
		return array(
			'opciones' => array(
				array('name' => 'admin_gestionusuario', 'titulo' => 'Administrar Usuarios y Grupos'),
				array('name' => 'admin_gestionpermiso', 'titulo' => 'Administrar Acciones y Funcionalidades'),
			),
			'llevalinkdevuelta' => false,
			'titulo' => 'Administraci&oacute;n del Sistema'
		);
	}

	/**
	 * @Route("/gestionusuario", name="admin_gestionusuario")
	 * @Template("FrameworkSeguridadBundle:Admin:opcionizador.html.twig")
	 */
	public function gestionUsuariosAction() {
		return array(
			'opciones' => array(
				array('name' => 'admin_gestionusuario_usuario', 'titulo' => 'Administrar Usuarios'),
				array('name' => 'admin_gestionusuario_grupo', 'titulo' => 'Administrar Grupos'),
			),
			'llevalinkdevuelta' => true,
			'titulo' => 'Administraci&oacute;n del Usuarios y Grupos'
		);
	}

	/**
	 * @Route("/gestionpermiso", name="admin_gestionpermiso")
	 * @Template("FrameworkSeguridadBundle:Admin:opcionizador.html.twig")
	 */
	public function gestionPermisosAction() {
		return array(
			'opciones' => array(
				array('name' => 'admin_gestionpermiso_accion', 'titulo' => 'Administrar Acciones'),
				array('name' => 'admin_gestionpermiso_funcionalidad', 'titulo' => 'Administrar Funcionalidades'),
			),
			'llevalinkdevuelta' => true,
			'titulo' => 'Administraci&oacute;n de Acciones y Funcionalidades'
		);
	}

	/**
	 * @Route("/login",name="admin_login")
	 * @Template("FrameworkSeguridadBundle:Admin:login.html.twig")
	 */
	public function adminLoginAction() {
		$request = $this->getRequest();
		$session = $request->getSession();

		if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
			$error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
		} else {
			$error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
			$session->remove(SecurityContext::AUTHENTICATION_ERROR);
		}

		return array(
			// last username entered by the user
			'last_username' => $session->get(SecurityContext::LAST_USERNAME),
			'error' => $error,
		);
	}

}

?>