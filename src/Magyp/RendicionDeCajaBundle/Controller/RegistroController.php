<?php

namespace Magyp\RendicionDeCajaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\Usuario;


use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

/** 
 * @author Fernando
 * @Route("/registro")
 * @Template()
 */
class RegistroController extends Controller {

	/**
	 * Displays a form to create a new Usuario entity.
	 *
	 * @Route("/nuevo", name="registro_nuevo")
	 * @Template()
	 */
	public function nuevoAction() {
		$entity = new Usuario();
		$form = $this->createForm(new \Magyp\RendicionDeCajaBundle\Form\UsuarioType(), $entity);
		return array(
			'entity' => $entity,
			'form' => $form->createView(),
			'error' => null
		);
	}

	/**
	 * Creates a new Usuario con operador entity.
	 *
	 * @Route("/crear", name="registro_crear")
	 * @Method("post")
	 * 
	 */
	public function crearAction() {

		$usuario = new Usuario();
		$form = $this->createForm(new \Magyp\RendicionDeCajaBundle\Form\UsuarioType(), $usuario);
		$request = $this->getRequest();
		$form->handleRequest($request);
		//var_dump($usuario);
		if($form->isValid()){
			$em = $this->getDoctrine()->getManager();
			
			echo 'es valido<br><br>';
			$existemail = $em->getRepository('MagypRendicionDeCajaBundle:Usuario')->findOneByEmail($usuario->getEmail());
			if(!is_null($existemail)){ return new Response("el e-mail {$usuario->getEmail() } ya existe"); }
			$existeusuario = $em->getRepository('MagypRendicionDeCajaBundle:Usuario')->findOneByUsername($usuario->getUsername());
			if(!is_null($existeusuario)){ return new Response("el usuario {$usuario->getUsername()} ya existe");}
			
			$factory = $this->get('security.encoder_factory');
			$encoder = $factory->getEncoder($usuario);
			$usuario->setPassword($encoder->encodePassword($usuario->getPassword(),$usuario->getSalt()));
			$em->persist($usuario);
			$em->flush();
			
			//return new Response('se ha creado el usuario');
		}else{
			echo 'es invalido';
		}
        return $this->redirect($this->generateUrl('home'));
		

	}

	
}

?>