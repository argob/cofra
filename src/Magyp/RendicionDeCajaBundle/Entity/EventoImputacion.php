<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Magyp\RendicionDeCajaBundle\Entity\EventoImputacion
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Magyp\RendicionDeCajaBundle\Entity\EventoImputacionRepository")
 */
class EventoImputacion extends Evento
{
//    /**
//     * @var integer $id
//     *
//     * @ORM\Column(name="id", type="integer")
//     * @ORM\Id
//     * @ORM\GeneratedValue(strategy="AUTO")
//     */
//    private $id;

    /**
     * @var \Magyp\RendicionDeCajaBundle\Entity\Imputacion $imputacion
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Imputacion")
     * @ORM\JoinColumn(name="imputacion_id",referencedColumnName="id")
     */
    private $imputacion;

//    /**
//     * @var \stdClass $evento
//     *
//     * @ORM\Column(name="evento", type="object")
//     */
//    private $evento;



    /**
     * Set imputacion
     *
     * @param \Magyp\RendicionDeCajaBundle\Entity\Imputacion $imputacion
     * @return EventoImputacion
     */
    public function setImputacion($imputacion)
    {
        $this->imputacion = $imputacion;
    
        return $this;
    }

    /**
     * Get imputacion
     *
     * @return \Magyp\RendicionDeCajaBundle\Entity\Imputacion 
     */
    public function getImputacion()
    {
        return $this->imputacion;
    }

		function __construct($usuario, $tipoEvento, $imputacion, $imputacionAnterior = null, $request = null) {
		$this->setUsuario($usuario);
		$this->setFecha(new \DateTime());
		$this->setImputacion($imputacion);
		$this->setTipoEvento($tipoEvento);
		if(!is_null($request))$this->request = $request;
		if($tipoEvento == self::MODIFICIACION){
			//echo '<br>es modificacion';
			$modifaciones = $this->BuscarCambios($imputacionAnterior); // busca diferencias entre El comprobante This y el comprobanteAnterior.
			//var_dump($modifaciones);
			if(count($modifaciones)){ // si hay modifiaciones
				$this->setListaDeCambios($modifaciones); // asi o de otra forma,. revisar.
			}
            $this->mensajeActualizado('Accion','Se han guardado los datos de la imputacion ' .$this->getImputacion()->getCodigo());		    
            
        }
		
	}
		function BuscarCambios($imputacionAnterior){
		$item = array();
		if(strcmp($imputacionAnterior->getDescripcion(), $this->getImputacion()->getDescripcion())){		//si hay cambios logue esto.
			$item[] = new LogRegistroDato($this, 'Descripcion', $imputacionAnterior->getDescripcion(), $this->getImputacion()->getDescripcion());
		}
		if(strcmp($imputacionAnterior->getCodigo(), $this->getImputacion()->getCodigo())){
			$item[] = new LogRegistroDato($this, 'Codigo', $imputacionAnterior->getCodigo(), $this->getImputacion()->getCodigo());
		}
		// Guardo el Id del tipo no es necesario guardar la entidad
		if($imputacionAnterior->getTipo()->getId() != $this->getImputacion()->getTipo()->getId()){
			$item[] = new LogRegistroDato($this, 'Tipo', $imputacionAnterior->getTipo()->getId(), $this->getImputacion()->getTipo()->getId());
		}

		return $item;
	}
    
}
