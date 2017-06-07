<?php

namespace Magyp\RendicionDeCajaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\Usuario;
use Magyp\RendicionDeCajaBundle\Form\UsuarioType;

use Framework\SeguridadBundle\Form\PermisosFuncionalidadType;

use Magyp\RendicionDeCajaBundle\Form\UsuarioSoloPasswordType;

use Magyp\RendicionDeCajaBundle\Form\UsuarioEditarType;

use Magyp\RendicionDeCajaBundle\Entity\ValidaUsuario;

class UsuarioPasswordController extends Controller
{
    /**
	 * 
	 * @Route("/perdielpassword", name="perdielpassword")
	 * @Template("MagypRendicionDeCajaBundle:UsuarioPassword:perdielpassword.html.twig")
	 */
	public function perdiElPasswordAction() { 
		$form = $this->createFormBuilder()
				->add('email', "email",array('attr' => array('placeholder' => 'Ingrese su Email')))
				->getForm()
				->createView();
		return array("form" => $form);
	}

    /**
	 * @Route("/enviarlinkcambiopasswordcos/{id}", name="cosmail")
	 * @Template() 
	 */
    
    public function enviarLinkCambioPasswordCosAction($id) {
			$em = $this->getDoctrine()->getManager();
            $usuario = $em->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($id);
			
			/* @var $usuario UsuarioExterno */
			if ($usuario) {
				$recuperaPass = $em->getRepository("MagypRendicionDeCajaBundle:ValidaUsuario")
						->findOneBy(array(
					"usuario" => $usuario->getId(),
					"motivo" => ValidaUsuario::MODIFICACION_CLAVE)
				);
				if (!(bool) $recuperaPass) {
					$recuperaPass = new ValidaUsuario($usuario, ValidaUsuario::MODIFICACION_CLAVE);
					$em->persist($recuperaPass);
					$em->flush();
				}
				$message = \Swift_Message::newInstance()
						->setSubject('Sistema Cofra')
						->setFrom('cofra_noreply@magyp.gob.ar')
						->setReplyTo('cofra_noreply@magyp.gob.ar')
						->setTo($usuario->getEmail())
						->setBody(
						"Se ha solicitado su alta de usuario '{$usuario->getUsername()}' en esta casilla de mail. Si usted no ha solicitado el alta, omita este mensaje");
				$this->get('mailer')->send($message);
				return $this->render("MagypRendicionDeCajaBundle:UsuarioPassword:mensajecos.html.twig", array(
							'mensaje' => "Se ha enviado un mail a la casilla {$usuario->getEmail()} con el aviso del alta al usuario."
						));
			} else {
				return $this->render("MagypRendicionDeCajaBundle:UsuarioPassword:mensajecos.html.twig", array(
							'mensaje' => "Mail no registrado"
						));
			}
		return $this->redirect($this->generateUrl("usuario"));
	}

    
	/**
	 * @Route("/enviarlinkcambiopassword", name="perdielpassword_enviamail")
	 * @method("post")
	 * @Template("MagypRendicionDeCajaBundle:UsuarioPassword:perdielpassword.html.twig")
	 */
	public function enviarLinkCambioPasswordAction() {
		$obj = new \stdClass();
		$obj->email = null;
		$form = $this->createFormBuilder($obj)
				->add('email', "email")
				->getForm();
		$request = $this->getRequest();
		$form->handleRequest($request);
		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
            $usuario = $em->getRepository('MagypRendicionDeCajaBundle:Usuario')->findOneByEmail($obj->email);
			/* @var $usuario UsuarioExterno */
			if ($usuario) {
                if($usuario->getBloqueada()) return $this->redirect($this->generateUrl('login_usuario_bloqueado'));
				$recuperaPass = $em->getRepository("MagypRendicionDeCajaBundle:ValidaUsuario")
						->findOneBy(array(
					"usuario" => $usuario->getId(),
					"motivo" => ValidaUsuario::MODIFICACION_CLAVE)
				);
				if (!(bool) $recuperaPass) {
					$recuperaPass = new ValidaUsuario($usuario, ValidaUsuario::MODIFICACION_CLAVE);
					$em->persist($recuperaPass);
					$em->flush();
				}
				$message = \Swift_Message::newInstance()
						->setSubject('Cambio de Contraseña Sistema Cofra')
						->setFrom('cofra_noreply@magyp.gob.ar')
						->setReplyTo('cofra_noreply@magyp.gob.ar')
						->setTo($obj->email)
						->setBody(
						"Se ha solicitado el cambio de contraseña para el usuario '{$usuario->getUsername()}' en esta casilla de mail.
Para cambiar su contraseña ingrese en la siguiente dirección " . $this->generateUrl("hash", array("hash" => $recuperaPass->getHash()), true) . "
	
Si usted no ha solicitado el cambio de contraseña omita este mensaje");
				$this->get('mailer')->send($message);
				return $this->render("MagypRendicionDeCajaBundle:UsuarioPassword:mensaje.html.twig", array(
							'mensaje' => "Se ha enviado un mail a la casilla {$usuario->getEmail()} con las instrucciones para cambiar la contraseña"
						));
			} else {
				return $this->render("MagypRendicionDeCajaBundle:UsuarioPassword:mensaje.html.twig", array(
							'mensaje' => "Mail no registrado"
						));
			}
		}
		return $this->redirect($this->generateUrl("login"));
	}

    /**
	 * @Route("/validaHash/{hash}", name="hash")
	 * @Template() 
	 */
	public function validaHashAction($hash) {
		$em = $this->getDoctrine()->getManager();
		$validaUsuario = $em->getRepository("MagypRendicionDeCajaBundle:ValidaUsuario")->findOneByHash($hash);
		/* @var $validaUsuario ValidaUsuario */
		if ($validaUsuario) {
			$usuario = $validaUsuario->getUsuario();
			/* @var $usuario UsuarioExterno */
			$motivo = $validaUsuario->getMotivo();
			switch ($motivo) {
				case ValidaUsuario::ALTA:
					/*$usuario->setActivo(true);
					$em->persist($usuario);
					$mensaje = "Se ha activado el usuario {$usuario->getUsername()}";*/
					break;
				case ValidaUsuario::CANCELACION:
					/*$operador = $em->getRepository("RegistroPadronBundle:Inscripcion\Operador")->findOneByUsuario($usuario->getId());
					//$operador = $usuario->getOperador();
					$dom1 = $operador->getDomicilioReal();
					$dom2 = $operador->getDomicilioEspecial();
					$em->remove($usuario);
					$em->remove($operador);
					$em->remove($dom1);
					$em->remove($dom2);
					$mensaje = "Se ha cancelado el alta para el usuario {$usuario->getUsername()}";*/
					break;
				case ValidaUsuario::MODIFICACION_CLAVE:
					$obj = new \stdClass();
					$obj->hash = $hash;
					$obj->password = null;
					$form = $this->createFormBuilder($obj)
							->add("hash", "hidden")
							->add('password', 'repeated', array(
								"type" => "password",
								"first_name" => "Clave",
								"second_name" => "Confirmar_clave",
								"invalid_message" => "La contraseña no concuerda"))
							->getForm()
							->createView();
					return $this->render("MagypRendicionDeCajaBundle:UsuarioPassword:cambiopassword.html.twig", array("form" => $form, "hash" => $hash));
					break;
				default:
					return $this->render("MagypRendicionDeCajaBundle:UsuarioPassword:mensaje.html.twig", array(
								'mensaje' => "La dirección ingresada no es válida"
							));
			}
			$validas = $em->getRepository("MagypRendicionDeCajaBundle:ValidaUsuario")->findByUsuario($usuario->getId());
			foreach ($validas as $validador) {//elimino todas las solicitudes que tenga el usuario 
				$em->remove($validador);
			}
			$em->flush();
			return $this->render("MagypRendicionDeCajaBundle:UsuarioPassword:mensaje.html.twig", array('mensaje' => $mensaje));
		} else {
			return $this->render("MagypRendicionDeCajaBundle:UsuarioPassword:mensaje.html.twig", array(
						'mensaje' => "La dirección ingresada no es válida"
					));
		}
	}
    
    /**
	 * @Route("/setearnuevopassword/{hash}", name="cambiopassword_seteanuevo")
	 * @method("post")
	 */
	public function setearnuevopasswordAction($hash) {
		$em = $this->getDoctrine()->getManager();
		$recupera = $em->getRepository("MagypRendicionDeCajaBundle:ValidaUsuario")->findOneByHash($hash);
		if ($recupera) {
			$obj = new \stdClass();
			$obj->hash = $hash;
			$obj->password = null;
$form = $this->createFormBuilder($obj)
							->add("hash", "hidden")
							->add('password', 'repeated', array(
								"type" => "password",
								"first_name" => "Clave",
								"second_name" => "Confirmar_clave",
								"invalid_message" => "La contraseña no concuerda"))
							->getForm();
			$request = $this->getRequest();
			$form->handleRequest($request);
			if ($form->isValid()) {
                if ($obj->password === null){
                    $cMensajeError= "La contraseña no puede tener espacios.";
                    $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
                    $mensaje->setMensaje('Error', $cMensajeError)		    
                        ->Error()->NoExpira()
                        ->Generar();
                    return $this->redirect($this->generateUrl('hash', array ('hash' => $hash)));
                }
				$usuario = $recupera->getUsuario();
				$usuario->setActivo(true);
				$encoder = $this->get('security.encoder_factory')->getEncoder($usuario);
				$usuario->setPassword($encoder->encodePassword($obj->password, $usuario->getSalt()));

				$em->persist($usuario);
				$validas = $em->getRepository("MagypRendicionDeCajaBundle:ValidaUsuario")->findByUsuario($usuario->getId());
				foreach ($validas as $validador) {//elimino todas las solicitudes que tenga el usuario 
					$em->remove($validador);
				}
				//$em->remove($recupera);
				$em->flush();
                $cMensajeError= "Cambio de contraseña realizado correctamente.";
                    $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
                    $mensaje->setMensaje('Exito', $cMensajeError)		    
                        ->Exito()
                        ->Generar();
                return $this->redirect($this->generateUrl('login'));
			}
            $cMensajeError= "Las contraseñas ingresadas no coinciden";
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
            $mensaje->setMensaje('Error', $cMensajeError)		    
                ->Error()->NoExpira()
                ->Generar();
            return $this->redirect($this->generateUrl('hash', array ('hash' => $hash)));
		}
		return $this->redirect($this->generateUrl('login'));
	}
    
}

?>
    