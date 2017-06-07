<?php

namespace Magyp\RendicionDeCajaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Magyp\RendicionDeCajaBundle\Form\UsuarioSoloPasswordType;
use \Symfony\Component\HttpFoundation\Request;

class LoginController extends BaseCofraController {

    /**
     * @Route("/sistema/login", name="login")
     * @Template()
     */
    public function loginAction(Request $request) {
        //$request = $this->getRequest();
        $session = $request->getSession();
//		echo '<pre>';
//		var_dump($session);
//		var_dump($request->attributes);
//		echo '</pre>';

        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        /** solo entra una vez * */
        if ($session->has("cuentabloqueada")) {
            $this->AvisarPorMailBloqueoDeCuenta();
            //	die('controller');
            return $this->redirect($this->generateUrl('login_usuario_bloqueado'));
        }
        /** solo entra una vez * */
        if ($session->has("avisarpormail"))
            $this->AvisarPorMailErrorDeLogueo();

        if (!is_null($error)) {
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
            $mensaje->setMensaje('Logueo', 'El usuario o contraseña no son validos. O su cuenta ha sido bloqueada.')->Error()->Generar();
            $em = $this->getDoctrine()->getManager();
            //var_dump($request);
            //die;
        } else {
            
        }
        if (!is_null($this->getUsuario())) { // buscar forma de saber si esta logueado
            return $this->redirect($this->generateUrl('home'));
        }


        return array(
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error' => $error,
        );
    }

    /**
     * @Route("/sistema/bienvenido", name="bienvenido")
     * @Template()
     */
    public function bienvenidoAction() {


        return array();
        //return new Response('Ud se ha logueado. bienvenido al sistema.');
    }

    /**
     * 
     * @Route("/sistema/home", name="home")
     * @Template("MagypRendicionDeCajaBundle:Home:home.html.twig")
     */
    public function homeAction() {
        //	var_dump($this->container);
        $securitycontext = $this->get('security.context');
        //ladybug_dump($securitycontext);
//        $this->get('ladybug')->log($securitycontext);

        if ($securitycontext->isGranted('ROLE_RENDICION')) {
            return array();
        }
        if ($securitycontext->isGranted('ROLE_TESORERIA')) {
            return $this->redirect($this->generateUrl('te_home'));
        }
        if ($securitycontext->isGranted('ROLE_AF')) {
            return $this->redirect($this->generateUrl('af_home'));
        }
        if ($securitycontext->isGranted('ROLE_USUARIO')) {
            return $this->redirect($this->generateUrl('usuario'));
        }
        return $this->redirect($this->generateUrl('home'));
        //}					
        return array();
    }

    /**
     * @Route("/unusuario", name="unusuario")
     * @Template()
     */
    public function unusuarioAction() {
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository('MagypRendicionDeCajaBundle:Usuario')->findAll();
        //$usuario = $em->getRepository('MagypRendicionDeCajaBundle:Usuario')->find(4);
        echo count($usuario);
        //var_dump($usuario);
        return new Response();
    }

    /**
     * @Route("/adios", name="adios")
     * @Template()
     */
    public function adiosAction() {
        return new Response('ud ha salido del sistema con exito.');
    }

    /**
     * @Route("/nada", name="nada")
     * @Template()
     */
    public function nadaAction() {
        echo "nada";
        return new Response();
    }

    /**
     * @Route("/sistema/cambiopasswordporcos", name="cambiopasswordporcos")
     * @Template()
     */
    public function cambiopasswordporcosAction() {
        $id = $this->getUsuario()->getId();

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }

        $editForm = $this->createForm(new UsuarioSoloPasswordType(), $entity);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * @Route("/sistema/prehome", name="prehome")
     */
    public function preHomeAction(Request $request) {
        /**
         * ultimo martes: decidir si avsar o no que la cuenta fue bloqueada cuando se loguea bien, si dejo el bloqueado del usuario original, es lo mismo
         * que cuando el usuario pone mal su password
         */
        $usuario = $this->getUsuario();
        if ($this->getUsuario()->getBloqueada())
            return $this->redirect($this->generateUrl('login_usuario_bloqueado'));
        $em = $this->getDoctrine()->getManager();
        $session = new \Magyp\RendicionDeCajaBundle\Entity\Session($usuario, $request);
        $session->BorrarSessionesAnteriores($usuario, $em);
        if ($session->ActivarAvisodeSession() == true)
            return $this->forward("MagypRendicionDeCajaBundle:Login:seguridadsession");

        $usuario->LimpiarErrorLogueo();
        $em->persist($usuario);
        $em->flush();

        $securitycontext = $this->get('security.context');
        
        if ($this->getUsuario()->getArea()->esTesoreria()){
            return $this->redirect($this->generateUrl('te_seleccionar_tesoreria')); 
        }

        if ($securitycontext->isGranted('ROLE_TESORERIA')) {
            return $this->redirect($this->generateUrl('te_home'));
        }

        if ($securitycontext->isGranted('ROLE_AF')) {
            return $this->redirect($this->generateUrl('af_home'));
        }

        if ($securitycontext->isGranted('ROLE_GENERAL')) {
            return $this->redirect($this->generateUrl('home'));
        }

        if ($securitycontext->isGranted('ROLE_USUARIO')) {
            return $this->redirect($this->generateUrl('usuario'));
        }

        return $this->redirect($this->generateUrl('home'));
    }

    /**
     * @Route("/sistema/seguridad/session", name="sistema_seguridad_session")
     * @Template()
     */
    public function seguridadsessionAction(Request $request) {

        /** en la otra pantalla entra antes, asi q se repite aca */
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUsuario();
        $usuario->LimpiarErrorLogueo();
        $em->persist($usuario);
        $em->flush();

        $securitycontext = $this->get('security.context');
        if ($securitycontext->isGranted('ROLE_USUARIO')) {
            $continuar_link = 'usuario';
        }
        if ($securitycontext->isGranted('ROLE_AF')) {
            $continuar_link = 'af_home';
        }
        if ($securitycontext->isGranted('ROLE_GENERAL')) {
            $continuar_link = 'home';
        }
        if ($securitycontext->isGranted('ROLE_TESORERIA')) {
            $continuar_link = 'te_home';
        }
        $otraubicacion = false;
        $doblesession = false;

        if ($request->getSession()->get("UbicacionDiferente") == 'true')
            $otraubicacion = true;
        if ($request->getSession()->get("DobleLogueoEncontrado") == 'true')
            $doblesession = true;

//			var_dump($otraubicacion);
//			var_dump($doblesession);
        return array('continuar_link' => $continuar_link, 'doblesession' => $doblesession, 'otraubicacion' => $otraubicacion);
    }

    /**
     * @Route("/login/bloqueado", name="login_usuario_bloqueado")
     * @Template()
     */
    public function usuariobloqueadoAction(Request $request) {
        $continuar_link = "login";
        $request->getSession()->remove("cuentabloqueada");
        return array("continuar_link" => $continuar_link);
    }

    public function AvisarPorMailErrorDeLogueo(Request $request) {

        $idusuario = $request->getSession()->get("avisarpormail");
        if (!is_numeric($idusuario))
            return;
        $usuario = $this->getDoctrine()->getManager()->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($idusuario);
        if (empty($usuario)) {
            echo "no se encontro el usuario";
            return;
        }
        $message = \Swift_Message::newInstance()
                ->setSubject('Alerta Autenticacion Cofra')
                ->setFrom('cofra_noreply@magyp.gob.ar')
                ->setReplyTo('cofra_noreply@magyp.gob.ar')
                ->setTo($usuario->getEmail())
                ->setBody(
                "Se ha detectado varios intentos de logueo con su usuario y por seguridad se envia este mail para que ud este al tanto, si el error ha sido por usted ignore este mensaje.");
        $this->get('mailer')->send($message);
        $request->getSession()->remove("avisarpormail");
    }

    public function AvisarPorMailBloqueoDeCuenta(Request $request) {

        $idusuario = $request->getSession()->get("cuentabloqueada");
        if (!is_numeric($idusuario))
            return;
        $usuario = $this->getDoctrine()->getManager()->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($idusuario);
        if (empty($usuario)) {
            echo "no se encontro el usuario";
            return;
        }
        if (!$usuario->AvisarPorMailBloqueoDeCuenta())
            return;
        $message = \Swift_Message::newInstance()
                ->setSubject('Cofra - Su cuenta ha sido bloqueada')
                ->setFrom('cofra_noreply@magyp.gob.ar')
                ->setReplyTo('cofra_noreply@magyp.gob.ar')
                ->setTo($usuario->getEmail())
                ->setBody(
                "Se ha detectado una exesiva cantidad de erores de logueo en su usuario del sistema Cofra, por la politica de seguridad se ha bloqueado su usuario. Para restableser su cuenta debe comunicarse con Mesa De Ayuda de Informática al interno 42600. e informarle lo sucedido.");
        $this->get('mailer')->send($message);
        $request->getSession()->remove("avisarpormail");
    }

}
