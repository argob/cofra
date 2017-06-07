<?php

namespace Magyp\RendicionDeCajaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magyp\RendicionDeCajaBundle\Entity\Leyenda;
use Magyp\RendicionDeCajaBundle\Form\LeyendaType;

use JMS\SecurityExtraBundle\Annotation\Secure;
/**
 * Leyenda controller.
 *
 * @Route("sistema/af/leyenda")
 */
class LeyendaController extends BaseCofraController
{
    /**
     * Lists all Leyenda entities.
     *
     * @Route("/", name="sistema_af_leyenda")
     * @Secure(roles="ROLE_AF")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MagypRendicionDeCajaBundle:Leyenda')->Todas();

        return array(
            'entities' => $entities,
        );
    }


    /**
     * Displays a form to create a new Leyenda entity.
     *
     * @Route("/new", name="sistema_af_leyenda_new")
     * @Secure(roles="ROLE_AF")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Leyenda();
        $form   = $this->createForm(new LeyendaType(), $entity);

        return array(
            'entity' => $entity,
	    
        );
    }

    /**
     * Creates a new Leyenda entity.
     *
     * @Route("/create", name="sistema_af_leyenda_create")
     * @Secure(roles="ROLE_AF")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:Leyenda:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $anio= $request->request->get('anio');
        $leyenda= $request->request->get('leyenda');

        $fecha=  new \DateTime;
        $fecha->setDate($anio, 1, 1);
        $fecha->setTime(0, 0, 0);
        
        $em = $this->getDoctrine()->getManager();
        $em->clear();
        $eLeyenda= new Leyenda;
        
        /* @var $eLeyenda Leyenda */
        $eLeyenda->setAnio( $fecha );
        $eLeyenda->setLeyenda($leyenda);
        $eLeyenda->setAsignado(false);
        
		$this->getCofra()->addEventoLeyenda(\Magyp\RendicionDeCajaBundle\Entity\Evento::NUEVO, $eLeyenda);
	
        $em->persist($eLeyenda);
        $em->flush();
        
        return $this->redirect($this->generateUrl('sistema_af_leyenda'));
    }

    /**
     * Displays a form to edit an existing Leyenda entity.
     *
     * @Route("/{id}/edit", name="sistema_af_leyenda_edit")
     * @Secure(roles="ROLE_AF")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagypRendicionDeCajaBundle:Leyenda')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Leyenda entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }

    /**
     * Edits an existing Leyenda entity.
     *
     * @Route("/{id}/update", name="sistema_af_leyenda_update")
     * @Secure(roles="ROLE_AF")
     * @Method("POST")
     * @Template("MagypRendicionDeCajaBundle:Leyenda:index.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $anio= $request->request->get('anio');
        $leyenda= $request->request->get('leyenda');
        //    echo date("Y", mktime(0, 0, 0, 7, 1, $anio));

        $fecha=  new \DateTime;
        $fecha->setDate($anio, 1, 1);
        $fecha->setTime(0, 0, 0);
        
        $em = $this->getDoctrine()->getManager();
        $em->clear();
        $eLeyenda = $em->getRepository('MagypRendicionDeCajaBundle:Leyenda')->find($id);
	
        if (!$eLeyenda) {
            throw $this->createNotFoundException('Unable to find Leyenda entity.');
        }
	$leyendaAnterior = clone $eLeyenda;
        /* @var $eLeyenda Leyenda */
        $eLeyenda->setAnio( $fecha );
        $eLeyenda->setLeyenda($leyenda);
        
	$this->getCofra()->addEventoLeyenda(\Magyp\RendicionDeCajaBundle\Entity\Evento::MODIFICIACION, $eLeyenda, $leyendaAnterior);
        $em->persist($eLeyenda);
        $em->flush();

        return $this->redirect($this->generateUrl('sistema_af_leyenda'));

    }
    
    
     /**
     * Edits an existing Leyenda entity.
     *
     * @Route("/{id}/actual", name="sistema_af_leyenda_actual")
     * @Secure(roles="ROLE_AF")
     * @Template()
     */
    public function actualAction( $id)
    {
        $em = $this->getDoctrine()->getManager();
        $em->clear();
        $eLeyendas = $em->getRepository('MagypRendicionDeCajaBundle:Leyenda')->findAll();
        
        foreach ( $eLeyendas as $eLeyenda){
            if ( $eLeyenda->getId() == $id ){
		$loguear = false;
		if($eLeyenda->getAsignado() != true){$leyendaAnterior = clone $eLeyenda; $loguear= true; }
                $eLeyenda->setAsignado(true);		
		if($loguear){
		    $this->getCofra()->addEventoLeyenda(\Magyp\RendicionDeCajaBundle\Entity\Evento::MODIFICIACION, $eLeyenda, $leyendaAnterior);
		}
            }else{
                $eLeyenda->setAsignado(false);
            }
            $em->persist($eLeyenda);
            $em->flush();
        }
        

        return $this->redirect($this->generateUrl('sistema_af_leyenda'));

    }

    /**
     *
     * @Route("/borrar/{idleyenda}", name="sistema_af_leyenda_borrar")
     * @Secure(roles="ROLE_AF")
     */
    public function borrarAction($idleyenda)
    {
        $em = $this->getDoctrine()->getManager();
        $em->clear();
        $eLeyenda = $em->getRepository('MagypRendicionDeCajaBundle:Leyenda')->find($idleyenda);        
        $eLeyenda->setBorrado(true);

        $this->getCofra()->addEventoLeyenda(\Magyp\RendicionDeCajaBundle\Entity\Evento::BORRAR, $eLeyenda);
	
        $em->persist($eLeyenda);
        $em->flush();
        
        return $this->redirect($this->generateUrl('sistema_af_leyenda'));
    }    
    
    /**
     *
     * @Route("/papelera", name="sistema_af_leyenda_papelera")
     * @Secure(roles="ROLE_AF")
     * @Template()
     */
    public function papeleraAction()
    {
	$em = $this->getDoctrine()->getManager();
        $leyendas = $em->getRepository('MagypRendicionDeCajaBundle:Leyenda')->borradas();        
        
        return array('leyendas' => $leyendas);
    }    
    /**
     *
     * @Route("/restaurar/{idleyenda}", name="sistema_af_leyenda_restaurar")
     * @Secure(roles="ROLE_AF")
     * @Template()
     */
    public function restaurarAction($idleyenda)
    {
	$em = $this->getDoctrine()->getManager();
        $leyenda = $em->getRepository('MagypRendicionDeCajaBundle:Leyenda')->find($idleyenda);        
        $leyenda->setBorrado(0);
        $em->persist($leyenda);
	$this->getCofra()->addEventoLeyenda(\Magyp\RendicionDeCajaBundle\Entity\Evento::RESTAURAR, $leyenda);
        $em->flush();
	return $this->redirect($this->generateUrl('sistema_af_leyenda'));
    }    
    /**
     * @Route("/{id}/eventos", name="sistema_leyenda_eventos")
     * @Secure(roles="ROLE_LOG")
     * @Template()	
     */
    public function eventosAction($id)
    {
	$eventos = $this->getCofra()->getEventoLeyenda($id);
	//\Magyp\RendicionDeCajaBundle\Entity\LogRegistroEntidad::setEntityManager($em);
	
	return array('eventos' => $eventos);
        
    }    
    
}
