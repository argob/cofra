<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventoArea
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Magyp\RendicionDeCajaBundle\Entity\EventoAreaRepository")
 */
class EventoArea extends Evento
{

    /**
     * @var Magyp\RendicionDeCajaBundle\Entity\Area $area
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Area")
     * @ORM\JoinColumn(name="area_id",referencedColumnName="id")
     */
    private $area;

    /**
     * Set area
     *
     * @param Magyp\RendicionDeCajaBundle\Entity\Area $area
     * @return EventoArea
     */
    public function setArea($area)
    {
        $this->area = $area;
    
        return $this;
    }

    /**
     * Get area
     *
     * @return string Magyp\RendicionDeCajaBundle\Entity\Area
     */
    public function getArea()
    {
        return $this->area;
    }
    
    function __construct($usuario, $tipoEvento, $area, $areaAnterior = null, $request = null) {
	$this->setUsuario($usuario);
	$this->setFecha(new \DateTime());
	$this->setArea($area);
	$this->setTipoEvento($tipoEvento);
	if(!is_null($request))$this->request = $request;

	if($tipoEvento == self::MODIFICIACION ){	   
		$modifaciones = $this->BuscarCambios($areaAnterior); // busca diferencias entre El comprobante This y el comprobanteAnterior.
		if(count($modifaciones)){ // si hay modifiaciones
			$this->setListaDeCambios($modifaciones); // asi o de otra forma,. revisar.
		}
		$this->mensajeActualizado();
	}
	$this->mensaje();

	}	

	function BuscarCambios(Area $areaAnterior){
	    $item = array();	    
	    if($areaAnterior->getMonto() != $this->getArea()->getMonto()){		//si hay cambios logue esto.
		    $item[] = new LogRegistroDato($this, 'Monto', $areaAnterior->getMonto(), $this->getArea()->getMonto());
	    }
	    if($areaAnterior->getPrograma()!= $this->getArea()->getPrograma()){	
		    if(!is_null($areaAnterior->getPrograma())){
	 		$item[] = new LogRegistroEntidad($this,"Programa",LogRegistroEntidad::PROGRAMA, $areaAnterior->getPrograma() );
		    }
		}
	    if($areaAnterior->getActividad()!= $this->getArea()->getActividad()){			
			$item[] = new LogRegistroEntidad($this,"Actividad",LogRegistroEntidad::ACTIVIDAD, $areaAnterior->getActividad() );
		}
	    if($areaAnterior->getUg()!= $this->getArea()->getUg()){			
			$item[] = new LogRegistroEntidad($this,"UG",LogRegistroEntidad::UG, $areaAnterior->getUg() );
		}
	    if($areaAnterior->getff()!= $this->getArea()->getff()){			
			$item[] = new LogRegistroEntidad($this,"Financiamiento",LogRegistroEntidad::FF, $areaAnterior->getff() );
		}
	    if($areaAnterior->getResponsable()!= $this->getArea()->getResponsable()){	
		    if($areaAnterior->hasResponsable()){
			$item[] = new LogRegistroEntidad($this,"Responsable",LogRegistroEntidad::USUARIO, $areaAnterior->getResponsable() );
		    }
		}
	    if($areaAnterior->getSubresponsable()!= $this->getArea()->getSubresponsable()){
		    if($areaAnterior->hasSubResponsable()){			
			$item[] = new LogRegistroEntidad($this,"Subresponsable",LogRegistroEntidad::USUARIO, $areaAnterior->getSubresponsable() );
		    }
		}
	    return $item;
	}
	public function mensaje(){	
	  //  \Doctrine\Common\Util\Debug::dump($this->request);
		if(is_null($this->request)) return; // evito q explote otras partes q no usan mensajes.
		$tipoEvento = $this->getTipoEvento();
		if($tipoEvento == self::NUEVO)$this->mensajeNuevo("Accion", "Se ha creado una area nueva");
		if($tipoEvento == self::BORRAR)$this->mensajeBorrar("Accion", "Se Borro la Area con exito");
		if($tipoEvento == self::RESTAURAR)$this->mensajeRestaurar("Accion", "Se restauro la Area");
		if($tipoEvento == self::MODIFICIACION)$this->mensajeActualizado("Accion", "Se actualizaron los datos de la Area");
        if($tipoEvento == self::BAJA)$this->mensajeBorrar("Accion", "Se dio de Baja el Area con exito");
			
	}

}
