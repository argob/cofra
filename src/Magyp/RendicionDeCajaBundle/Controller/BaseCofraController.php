<?php
namespace Magyp\RendicionDeCajaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class BaseCofraController extends Controller
{
    
    /**
     * Get Cofra
     *
     * @return \Magyp\RendicionDeCajaBundle\Kernel\Cofra
     **/
    function getCofra() {
	$cofra = $this->get("cofra.kernel");
	$cofra->setController($this);
	return $cofra;
    }
    /**
     * Get Usuario
     * Obtiene el usuario Final del security.context.
     *
     * @return \Magyp\RendicionDeCajaBundle\Entity\Usuario
     **/
    function getUsuario(){	
        if (!empty ($this->get('session')->get('areaseleccionada'))){
            $em = $this->getDoctrine()->getManager();
            //echo("tengo seteado area fantasma");
            $area = $em->getRepository('MagypRendicionDeCajaBundle:Area')->find($this->get('session')->get('areaseleccionada'));
            $this->getUser()->setAreaSeleccionada($area);
            //var_dump($this->getUser()->getAreaSeleccionada());
            //die;
        }
	return $this->getUser();	
    }

}