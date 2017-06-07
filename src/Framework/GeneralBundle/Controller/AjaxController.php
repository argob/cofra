<?php

namespace Framework\GeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Route("/ajax")
 * 
 */
class AjaxController extends Controller {

    /**
     * @Route("/pedidos/partidos")
     * @method('POST')
     * @Template()
     */
    public function partidoAction() {
        $em = $this->getDoctrine()
                ->getManager();
        $request = $this->getRequest();
        $id_prov = $request->get('id_prov');

        $provincia = $em->getRepository('FrameworkGeneralBundle:Provincia')->find($id_prov);
        $objetos = $provincia->getPartidos();
        return $this->render('FrameworkGeneralBundle:ajax:combox.html.twig', array('objetos' => $objetos));
    }

    /**
     * @Route("/pedidos/localidades")
     * @method('POST')
     * @Template()
     */
    public function localidadAction() {
        $em = $this->getDoctrine()
                ->getManager();
        $request = $this->getRequest();
        $id_part = $request->get('id_part');
        $partido = $em->getRepository('FrameworkGeneralBundle:Partido')->find($id_part);
        $objetos = $partido->getLocalidades();
        return $this->render('FrameworkGeneralBundle:ajax:combox.html.twig', array('objetos' => $objetos));
    }

    /**
     * @Route("/pedidos/provincias")
     * @Template()
     */
    public function provinciaAction() {
        $em = $this->getDoctrine()
                ->getManager();
        $objetos = $em->getRepository('FrameworkGeneralBundle:Provincia')->findAll();
        return $this->render('FrameworkGeneralBundle:ajax:combox.html.twig', array('objetos' => $objetos));
    }

    /**
     * @Route("/pedidos/codpostal")
     * @Template()
     */
    public function codpostalAction() {
        $em = $this->getDoctrine()
                ->getManager();
        $request = $this->getRequest();
        $id_loc = $request->get('id_loc');
        $localidad = $em->getRepository('FrameworkGeneralBundle:Localidad')->find($id_loc);
        $objetos = $localidad->getCodpostales();
        return $this->render('FrameworkGeneralBundle:ajax:comboxcodpostal.html.twig', array('objetos' => $objetos));
    }

}
