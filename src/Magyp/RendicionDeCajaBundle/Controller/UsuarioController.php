<?php

namespace Magyp\RendicionDeCajaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\Usuario;
use Magyp\RendicionDeCajaBundle\Form\UsuarioType;
use Magyp\RendicionDeCajaBundle\Entity\EventoUsuario;
use Magyp\RendicionDeCajaBundle\Entity\Evento;


use Framework\SeguridadBundle\Form\PermisosFuncionalidadType;

use Framework\SeguridadBundle\Entity\PermisoComposite;

use Magyp\RendicionDeCajaBundle\Form\UsuarioSoloPasswordType;

use Magyp\RendicionDeCajaBundle\Form\UsuarioSoloPasswordFrontType;


use Magyp\RendicionDeCajaBundle\Entity\ValidaUsuario;

use JMS\SecurityExtraBundle\Annotation\Secure;
/**
 * Usuario controller.
 *
 * @Route("/sistema/usuario")
 */
class UsuarioController extends BaseCofraController
{
    /**
     * Lists all Usuario usuarios.
     *
     * @Route("/", name="usuario")
     * @Secure(roles="ROLE_USUARIO")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        //$usuarios = $em->getRepository('MagypRendicionDeCajaBundle:Usuario')->findAll();
        $qbusuarios = $em->getRepository('MagypRendicionDeCajaBundle:Usuario')->qb_Usuarios();
        
	$paginador =  $this->get('knp_paginator');
	$cantidad = $this->get('request')->query->get('cantidad');
	$cantidad = intval($cantidad) > 0  ? intval($cantidad) : 15;

	$usuarios = $paginador->paginate(
	    $qbusuarios->getQuery(),
	    $this->get('request')->query->get('page', 1),
	    $cantidad
	 );

        return array(
            'usuarios' => $usuarios,
        );
    }

    /**
     * Finds and displays a Usuario entity.
     *
     * @Route("/{id}/detalle", name="usuario_show")
     * @Secure(roles="ROLE_USUARIO")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Usuario entity.
     *
     * @Route("/nuevo", name="usuario_new")
     * @Secure(roles="ROLE_USUARIO")
     * @Template()
     */
    public function newAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new Usuario();
        $form   = $this->createForm(new UsuarioType(), $entity);
        //$form = $this->createForm(new \Magyp\RendicionDeCajaBundle\Form\UsuarioType(), $entity);
        
        //$ePermisos = $em->getRepository('FrameworkSeguridadBundle:UsuarioComposite')->find(1);
        /* @var $ePermisos FrameworkSeguridadBundle:UsuarioComposite */
        $ePermisos= new \Framework\SeguridadBundle\Entity\Funcionalidad;

        $editPermisosForm = $this->createForm(new PermisosFuncionalidadType(), $ePermisos);

        if (!$entity) {
                throw $this->createNotFoundException('Unable to find Usuario entity.');
        }
        if (!$ePermisos) {
                throw $this->createNotFoundException('Unable to find PermisosFuncionalidad entity.');
        }
           
  
        return array(
            'entity' => $entity,
            'epermisos'			=> $ePermisos,
            'editPermisos_Form'	=> $editPermisosForm->createView(),
            'form'   => $form->createView(),
            'error' => null,
        );
    }
   

    /**
     * Creates a new Usuario entity.
     *
     * @Route("/crear", name="usuario_create")
     * @Secure(roles="ROLE_USUARIO")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:Usuario:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $usuario = new Usuario();
		$form = $this->createForm(new \Magyp\RendicionDeCajaBundle\Form\UsuarioType(), $usuario);
		$request = $this->getRequest();
		$form->handleRequest($request);
		//var_dump($usuario);
		if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $nombredeusuario= $usuario->getUsername();
            if (!(preg_match ("/^[a-z\d_]{3,15}$/i", $nombredeusuario))) { 
                $cMensajeError= "El nombre de usuario tiene que ser mayor a 3 y menor a 15 caracteres, y solo se pueden usar letras en minuscula y numeros.";
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
                $mensaje->setMensaje('Error', $cMensajeError)		    
                    ->Error()->NoExpira()
                    ->Generar();
                $editPermisosForm = $this->createForm(new PermisosFuncionalidadType(), $usuario);
                return array(
                    'entity'      => $usuario,
                    'editPermisos_Form'	=> $editPermisosForm->createView(),
                    'form'   => $form->createView(),

                );
            }
		
            //$em->clear();
			$existemail = $em->getRepository('MagypRendicionDeCajaBundle:Usuario')->findOneByEmail($usuario->getEmail());
            if(!is_null($existemail)){ 
                $cMensajeError= "La direccion de correo {$usuario->getEmail()} ya esta asociado al usuario {$existemail->getUsername()}.  ";
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
                $mensaje->setMensaje('Error', $cMensajeError)		    
                    ->Error()->NoExpira()
                    ->Generar();
                $editPermisosForm = $this->createForm(new PermisosFuncionalidadType(), $usuario);
                return array(
                    'entity'      => $usuario,
                    'editPermisos_Form'	=> $editPermisosForm->createView(),
                    'form'   => $form->createView(),

                );
            }
			$existeusuario = $em->getRepository('MagypRendicionDeCajaBundle:Usuario')->findOneByUsername($usuario->getUsername());
			if(!is_null($existeusuario)){
                $cMensajeError= "El usuario {$existeusuario->getUsername()} ya existe.  ";
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
                $mensaje->setMensaje('Error', $cMensajeError)		    
                    ->Error()->NoExpira()
                    ->Generar();
                $editPermisosForm = $this->createForm(new PermisosFuncionalidadType(), $usuario);
                return array(
                    'entity'      => $usuario,
                    'editPermisos_Form'	=> $editPermisosForm->createView(),
                    'form'   => $form->createView(),

                );
            }
			//falla cuando persiste usuario, ahroa falta chekear si falla cuando agrega evento, hay varias cosa  q ver con eso
			//$factory = $this->get('security.encoder_factory');
			//$encoder = $factory->getEncoder($usuario);
			//$usuario->setPassword($encoder->encodePassword($usuario->getPassword(),$usuario->getSalt()));
			$em->persist($usuario);
//			$eventoUsuario = new EventoUsuario($this->getUsuario(),Evento::NUEVO,$this->getRequest(), $usuario);	    
//			$em->persist($eventoUsuario);
            $userCompleto = $this->getDoctrine()->getManager()->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($this->getUsuario()->getId());
			$em->refresh($userCompleto);
            $this->getCofra()->addEventoUsuario(Evento::NUEVO, $userCompleto);
			//$em->flush();

           $em = $this->getDoctrine()->getManager();

           if (!$usuario) {
                   throw $this->createNotFoundException('Unable to find Usuario o Grupo entity.');
           }
           $editPermisosForm = $this->createForm(new PermisosFuncionalidadType(), $usuario);
           $request = $this->getRequest();
           $editPermisosForm->handleRequest($request);

           if ($editPermisosForm->isValid()){
               $permisosasignados= $usuario->getPermisosContenidos();
               $cantidadpermisosasignados= count($permisosasignados);
               if ($cantidadpermisosasignados == 0){
                    $cMensajeError= "Debe seleccionar algun permiso.";
                    $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
                    $mensaje->setMensaje('Error', $cMensajeError)		    
                        ->Error()->NoExpira()
                        ->Generar();
                    $editPermisosForm = $this->createForm(new PermisosFuncionalidadType(), $usuario);
                    return array(
                        'entity'      => $usuario,
                        'editPermisos_Form'	=> $editPermisosForm->createView(),
                        'form'   => $form->createView(),

                    );
                //return $this->redirect($this->generateUrl('front_gestionusuario_editarpermisos', array ('id' => $usuario->getId())));
                
               }else{
                   $em->persist($usuario);
                   $em->flush(); 
                   return $this->redirect($this->generateUrl('cosmail', array('id' => $usuario->getId() )));
               }
            }
            die("Llame al 42600 error 101.");
            //return $this->redirect($this->generateUrl('cosmail', array('mail' => $usuario->getEmail() )));
		}else{
            $cMensajeError= "Las direcciones de mail no coinciden.";
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
            $mensaje->setMensaje('Error', $cMensajeError)		    
                ->Error()->NoExpira()
                ->Generar();
		}
        //return $this->redirect($this->generateUrl('usuario'));
        $editPermisosForm = $this->createForm(new PermisosFuncionalidadType(), $usuario);
        return array(
            'entity'      => $usuario,
            'editPermisos_Form'	=> $editPermisosForm->createView(),
            'form'   => $form->createView(),

        );

    }
    

    /**
     * Displays a form to edit an existing Usuario entity.
     *
     * @Route("/{id}/editar", name="usuario_edit")
     * @Secure(roles="ROLE_USUARIO")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }

        $editForm = $this->createForm(new UsuarioType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Usuario entity.
     *
     * @Route("/{id}/actualizar", name="usuario_update")
     * @Secure(roles="ROLE_USUARIO")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:Usuario:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
		$em->clear();
		
        $entity = $em->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($id);
		$usuariodestinoAnterior = clone $entity;
		
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new UsuarioType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            
            /*$factory = $this->get('security.encoder_factory');
			$encoder = $factory->getEncoder($entity);
            $entity->setPassword($encoder->encodePassword($entity->getPassword(),$entity->getSalt()));*/
            //$entity->setPassword( $entity->getPassword() );
             
            $usuario= $entity;
            $nombredeusuario= $entity->getUsername();
            if (!(preg_match ("/^[a-z\d_]{3,15}$/i", $nombredeusuario))) { 
                $cMensajeError= "El nombre de usuario tiene que ser mayor a 3 y menor a 15 caracteres, y solo se pueden usar letras en minuscula y numeros.";
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
                $mensaje->setMensaje('Error', $cMensajeError)		    
                    ->Error()->NoExpira()
                    ->Generar();
                $editPermisosForm = $this->createForm(new PermisosFuncionalidadType(), $usuario);
                return $this->redirect($this->generateUrl('sistema_comprobante_crear_listo'));
            }
            
            $aexistemail = $em->getRepository('MagypRendicionDeCajaBundle:Usuario')->findByEmail($usuario->getEmail());
            if(count($aexistemail) >= 2){ 
                $cMensajeError= "La direccion de correo {$usuario->getEmail()} ya esta asociada.  ";
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
                $mensaje->setMensaje('Error', $cMensajeError)		    
                    ->Error()->NoExpira()
                    ->Generar();
                return $this->redirect($this->generateUrl('sistema_comprobante_crear_listo'));
            }
            
            $aexisteusuario = $em->getRepository('MagypRendicionDeCajaBundle:Usuario')->findBy(array ("username"=>$usuario->getUsername()));
            if( (count($aexisteusuario) >= 1) && ( $aexisteusuario[0]->getId() != $id ) ){
                $cMensajeError= "El usuario ya existe.  ";
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
                $mensaje->setMensaje('Error', $cMensajeError)		    
                    ->Error()->NoExpira()
                    ->Generar();
                $editPermisosForm = $this->createForm(new PermisosFuncionalidadType(), $usuario);
                return $this->redirect($this->generateUrl('sistema_comprobante_crear_listo'));
            }
            
            
            $em->persist($entity);
			$usuariodestino = $entity;
            $this->getCofra()->addEventoUsuario(Evento::MODIFICIACION, $usuariodestino, $usuariodestinoAnterior);
            //$usuario = $em->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($this->getUsuario()->getId());
            //$eventoUsuario = new EventoUsuario($usuario,Evento::MODIFICIACION,$this->getRequest(), $entity);
            //$eventoUsuario = new EventoUsuario($this->getUsuario(),Evento::MODIFICIACION,$this->getRequest(), $entity);
			//$this->getCofra()->dump($usuario,2);
			//$this->getCofra()->dump($this->getUsuario(),2);
			//die;
			//var_dump($usuario);
			//var_dump($this->getUsuario());
		//	die;
            //$em->persist($eventoUsuario);
			$em->flush();
            return $this->redirect($this->generateUrl('sistema_comprobante_crear_listo'));
            //return $this->redirect($this->generateUrl('usuario', array('id' => $id)));
            
        }	
	
        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
        
        /*
        
        
       
		
        
			

           $em = $this->getDoctrine()->getManager();

           if (!$usuario) {
                   throw $this->createNotFoundException('Unable to find Usuario o Grupo entity.');
           }
           $editPermisosForm = $this->createForm(new PermisosFuncionalidadType(), $usuario);
           $request = $this->getRequest();
           $editPermisosForm->handleRequest($request);

           if ($editPermisosForm->isValid()){
               $permisosasignados= $usuario->getPermisosContenidos();
               $cantidadpermisosasignados= count($permisosasignados);
               if ($cantidadpermisosasignados == 0){
                    $cMensajeError= "Debe seleccionar algun permiso.";
                    $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
                    $mensaje->setMensaje('Error', $cMensajeError)		    
                        ->Error()->NoExpira()
                        ->Generar();
                    $editPermisosForm = $this->createForm(new PermisosFuncionalidadType(), $usuario);
                    return array(
                        'entity'      => $usuario,
                        'editPermisos_Form'	=> $editPermisosForm->createView(),
                        'form'   => $editForm->createView(),

                    );
                //return $this->redirect($this->generateUrl('front_gestionusuario_editarpermisos', array ('id' => $usuario->getId())));
                
               }else{
                   $em->persist($usuario);
                   $em->flush(); 
                   return $this->redirect($this->generateUrl('cosmail', array('mail' => $usuario->getEmail() )));
               }
            }
            die("Llame al 42600 error 101.");
            //return $this->redirect($this->generateUrl('cosmail', array('mail' => $usuario->getEmail() )));
		}else{
            $cMensajeError= "Las direcciones de mail no coinciden.";
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
            $mensaje->setMensaje('Error', $cMensajeError)		    
                ->Error()->NoExpira()
                ->Generar();
		}
        //return $this->redirect($this->generateUrl('usuario'));
        $editPermisosForm = $this->createForm(new PermisosFuncionalidadType(), $usuario);
        return array(
            'entity'      => $usuario,
            'editPermisos_Form'	=> $editPermisosForm->createView(),
            'form'   => $editForm->createView(),

        );

        
        
        
        
    }

        */
        
        
    }

    /**
     * Deletes a Usuario entity.
     *
     * @Route("/{id}/borrar", name="usuario_delete")
     * @Secure(roles="ROLE_USUARIO")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Usuario entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('usuario'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    /**
     * 
     *
     * @Route("/crear/listo", name="sistema_usuario_crear_listo")
     * @Template()
     */
    public function mensajeAction()
    {
        
        return array(
        );
    }
    
    
    /**
    * @Route("/{id}/permisos",name="front_gestionusuario_editarpermisos")
    * @Secure(roles="ROLE_USUARIO")
    * @Template()
    */
   public function editarPermisosAction($id){
           $em = $this->getDoctrine()->getManager();
           $entity = $em->getRepository('FrameworkSeguridadBundle:UsuarioComposite')->find($id);

           $editPermisosForm = $this->createForm(new PermisosFuncionalidadType(), $entity);

           if (!$entity) {
                   throw $this->createNotFoundException('Unable to find Usuario entity.');
           }
           return array(
                   'entity'			=> $entity,
                   'editPermisos_Form'	=> $editPermisosForm->createView(),
           );
   }

   /**
    * Agrega permisos a un usuario
    *
    * @Route("/{id}/modificarpermisos", name="front_gestionusuario_modificarpermisos")
    * @Secure(roles="ROLE_USUARIO")
    * @Method("post")
    * @Template("FrameworkSeguridadBundle:Usuario:editarPermisos.html.twig")
    */
   public function modificarPermisosAction($id){
           $em = $this->getDoctrine()->getManager();
           $entity = $em->getRepository('FrameworkSeguridadBundle:UsuarioComposite')->find($id);
           if (!$entity) {
                   throw $this->createNotFoundException('Unable to find Usuario o Grupo entity.');
           }
           $editPermisosForm = $this->createForm(new PermisosFuncionalidadType(), $entity);
           $request = $this->getRequest();
           $editPermisosForm->handleRequest($request);

           if ($editPermisosForm->isValid()){
                   $em->persist($entity);
                   $em->flush();
                   
                    $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                    $mensaje->setMensaje('Accion','Se guardaron los nuevos permisos exitosamente.')		    
                    ->Exito()
                    ->Generar();
                           
                   return $this->redirect($this->generateUrl('sistema_comprobante_crear_listo'));
                   //return $this->redirect($this->generateUrl('front_gestionusuario_editarpermisos', array('id' => $id)));
           }
           
  

           return array(
                   'entity'	  => $entity,
                   'editPermisos_Form'	=> $editPermisosForm->createView(),
           );
   }
   
    /**
     * Displays a form to edit an existing Usuario entity.
     *
     * @Route("/{id}/editarpassword", name="usuario_edit_password")
     * @Secure(roles="ROLE_USUARIO")
     * @Template()
     */
    public function editPasswordAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }

        $editForm = $this->createForm(new UsuarioSoloPasswordType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    
    
    /**
     * Edits an existing Usuario entity.
     *
     * @Route("/{id}/{estadodepassword}/actualizarpassword", name="usuario_update_password")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:Usuario:editPassword.html.twig")
     */
    public function updatePasswordAction(Request $request, $id, $estadodepassword )
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new UsuarioSoloPasswordType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $contraenaValidar= $entity->getPassword();
            $acontraenaValidar = explode(" ", $contraenaValidar);
            if ( ( $acontraenaValidar[0] === '' ) || ( count($acontraenaValidar) > 1 ) ){
                $cMensajeError= "La contraseña no puede tener espacios.";
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
                $mensaje->setMensaje('Error', $cMensajeError)		    
                    ->Error()->NoExpira()
                    ->Generar();
                if ($estadodepassword == 0 ){
                    return $this->redirect($this->generateUrl('prehome'));
                }else{
                    return $this->redirect($this->generateUrl('sistema_comprobante_crear_listo'));
                }
            }
            
            $entity->setSalt(md5(uniqid(null, true)));
            
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($entity);
            $entity->setPassword($encoder->encodePassword($entity->getPassword(),$entity->getSalt()));

            $entity->setCambiarPassword($estadodepassword);

            $em->persist($entity);
			$this->getCofra()->addEventoUsuario(Evento::CAMBIOPASSWORD, $entity);
           // $eventoUsuario = new EventoUsuario($this->getUsuario(),Evento::CAMBIOPASSWORD,$this->getRequest(), $entity);	    
            //$em->persist($eventoUsuario);
            $em->flush();
            
            if ($estadodepassword == 0 ){
                return $this->redirect($this->generateUrl('prehome'));
            }else{
                return $this->redirect($this->generateUrl('sistema_comprobante_crear_listo'));
            }
            //return $this->redirect($this->generateUrl('usuario'));
            //return $this->redirect($this->generateUrl('usuario', array('id' => $id)));
            
        }
        else {
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
                $mensaje->setMensaje('Error',"Las contraseñas ingresadas no coinciden.")		    
                ->Generar();
            if ($estadodepassword == 0 ){
                return $this->redirect($this->generateUrl('home'));
            }else{
                return $this->redirect($this->generateUrl('usuario_edit_password', array ('id' => $id)));
            }
		// para cuando haya errores centrales
//		
//		$mensajeerror = new \Magyp\MensajeBundle\Controller\MensajeError($request, null, $editForm);
//		//var_dump($mensajeerror->getErrorMessages($editForm));
//		//var_dump($editForm);
//		if($mensajeerror->hasErrores()){
//		    $mensajeerror
//			    ->NoExpira()
//			    ->Posision('central-top')
//			    //->Posision("'left':0, 'right':0, 'top':40")
//			    ->Generar();
//		    // este deberia ser alerta central
//		}
	}	
        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * @Route("/ver/{idusuario}", name="usuario_ver")
     * @Secure(roles="ROLE_USUARIO")
     * @Template("MagypRendicionDeCajaBundle:Usuario:index.html.twig")
     */
    public function verAction($idusuario)
    {
        $em = $this->getDoctrine()->getManager();

        $usuarios = array(); // para usar el mismo template;
        $usuarios[0] = $em->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($idusuario);

        return array(
            'usuarios' => $usuarios,
        );
    }    
    /**
     * @Route("/buscar", name="usuario_buscar")
     * @Secure(roles="ROLE_USUARIO")
     * @Template("MagypRendicionDeCajaBundle:Usuario:index.html.twig")
     * @Method({"POST"})
     */
    public function buscarAction()
    {
        $idusuario = $this->getRequest()->get('usuario_id');
        
        return $this->redirect($this->generateUrl('usuario_ver', array('idusuario' => $idusuario)));
    }    
    /**
     * @Route("/lista", name="sistema_usuario_lista")
     * @Secure(roles="ROLE_USUARIO")
     * @Template("MagypRendicionDeCajaBundle:Busqueda:usuarioLista.html.twig")
     * 
     */
    public function listaAction()
    {
        $em = $this->getDoctrine()->getManager();
        $usuarios  = $em->getRepository('MagypRendicionDeCajaBundle:Usuario')->findAll();
        return array('usuarios' => $usuarios);
    }        
    
    
    /**
     * Displays a form to edit an existing Usuario entity.
     *
     * @Route("/{id}/mostrar", name="usuario_mostrar")
     * @Template()
     */
    public function mostrarAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $eUsuario = $em->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($id);
        

        if (!$eUsuario) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }
        
        if ($eUsuario != $this->getUsuario() ){
            $mensajeAEnviar= "No tiene permisos para ver esta informacion";
                $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($this->getRequest());
                $mensaje->setMensaje('Error',$mensajeAEnviar)		    
                ->Error()->NoExpira()
                ->Generar();
            return $this->redirect($this->generateUrl('home'));
        }

        
        $editForm = $this->createForm(new UsuarioSoloPasswordFrontType(), $eUsuario);
        
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'usuario'      => $eUsuario,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    
    
    
       /**
     * Edits an existing Usuario entity.
     *
     * @Route("/{id}/upadtemostrar", name="usuario_update_password_mostrar")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:Usuario:mostrar.html.twig")
     */
    public function updateMostrarAction(Request $request, $id )
    {
        $em = $this->getDoctrine()->getManager();
        
        $entity = $em->getRepository('MagypRendicionDeCajaBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Usuario entity.');
        }

        $passwordAnterior= $request->request->get('passwordanterior');
        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($entity);
        if ( $entity->getPassword() != $encoder->encodePassword( $passwordAnterior, $entity->getSalt() ) ){
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
                $mensaje->setMensaje('Error',"La contraseña actual es incorrecta.")
                ->Error()->NoExpira()
                ->Generar();
            return $this->redirect($this->generateUrl('usuario_mostrar', array ('id' => $id)));
        }
        
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new UsuarioSoloPasswordFrontType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $entity->setSalt(md5(uniqid(null, true)));
            $entity->setPassword($encoder->encodePassword($entity->getPassword(),$entity->getSalt()));
            $em->persist($entity);
			$this->getCofra()->addEventoUsuario(Evento::MODIFICIACION, $entity);
//            $eventoUsuario = new EventoUsuario($this->getUsuario(),Evento::CAMBIOPASSWORD,$this->getRequest(), $entity);	    
//            $em->persist($eventoUsuario);
            $em->flush();

            return $this->redirect($this->generateUrl('home'));
            
        }else{
            $mensaje = new \Magyp\MensajeBundle\Controller\MensajeSession($request);
                $mensaje->setMensaje('Error',"Las contraseñas ingresadas no coinciden.")
                ->Error()->NoExpira()
                ->Generar();
            return $this->redirect($this->generateUrl('usuario_mostrar', array ('id' => $id)));
	}	
        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

}
