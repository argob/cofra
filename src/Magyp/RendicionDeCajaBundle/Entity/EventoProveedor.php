<?php

namespace Magyp\RendicionDeCajaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventoProveedor
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Magyp\RendicionDeCajaBundle\Entity\EventoProveedorRepository")
 */
class EventoProveedor extends Evento
{
//    /**
//     * @var integer
//     *
//     * @ORM\Column(name="id", type="integer")
//     * @ORM\Id
//     * @ORM\GeneratedValue(strategy="AUTO")
//     */
//    private $id;

    /**
     * @var Magyp\RendicionDeCajaBundle\Entity\Proveedor $proveedor
     * @ORM\ManyToOne(targetEntity="\Magyp\RendicionDeCajaBundle\Entity\Proveedor")
     * @ORM\JoinColumn(name="proveedor_id",referencedColumnName="id")
     */
    private $proveedor;

    



    /**
     * Set proveedor
     *
     * @param Magyp\RendicionDeCajaBundle\Entity\Proveedor $proveedor
     * @return EventoProveedor
     */
    public function setProveedor($proveedor)
    {
        $this->proveedor = $proveedor;
    
        return $this;
    }

    /**
     * Get proveedor
     *
     * @return string Magyp\RendicionDeCajaBundle\Entity\Proveedor
     */
    public function getProveedor()
    {
        return $this->proveedor;
    }
	
	function __construct($usuario, $tipoEvento, $proveedor, $proveedorAnterior = null, $request = null) {
		$this->setUsuario($usuario);
		$this->setFecha(new \DateTime());
		$this->setProveedor($proveedor);
		$this->setTipoEvento($tipoEvento);
		if(!is_null($request))$this->request = $request;
		if($tipoEvento == self::MODIFICIACION){
			//echo '<br>es modificacion';
			$modifaciones = $this->BuscarCambios($proveedorAnterior); // busca diferencias entre El comprobante This y el comprobanteAnterior.
			//var_dump($modifaciones);
			if(count($modifaciones)){ // si hay modifiaciones
				$this->setListaDeCambios($modifaciones); // asi o de otra forma,. revisar.
			}
			$this->mensajeActualizado('Accion','Se han guardado los datos del proveedor ' .$this->getProveedor()->getDescripcion());		    
		}
		if($tipoEvento == self::NUEVO)$this->mensajeNuevo('Accion','Se ha generado el Proveedor ' .$this->getProveedor()->getDescripcion());
		if($tipoEvento == self::BORRAR)$this->mensajeBorrar('Accion','Se ha borrado el proveedor ' .$this->getProveedor()->getDescripcion());
		if($tipoEvento == self::RESTAURAR)$this->mensajeRestaurar('Accion','Se ha restaurado el proveedor ' .$this->getProveedor()->getDescripcion());
		
		
	}
		function BuscarCambios($proveedorAnterior){
		$item = array();
		var_dump($proveedorAnterior->getDescripcion());
		var_dump($this->getProveedor()->getDescripcion());
		
		if(strcmp($proveedorAnterior->getDescripcion(), $this->getProveedor()->getDescripcion())){		//si hay cambios logue esto.
			$item[] = new LogRegistroDato($this, 'Descripcion', $proveedorAnterior->getDescripcion(), $this->getProveedor()->getDescripcion());
		}
		return $item;
	}
	
   
}
