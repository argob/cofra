<?php

namespace Framework\GeneralBundle\Form\Binder;

use Doctrine\ORM\Mapping as ORM;
use Framework\GeneralBundle\Entity\Domicilio;
use Framework\GeneralBundle\Entity\Direccion\DireccionUrbano;
use Framework\GeneralBundle\Entity\Direccion\DireccionRural;
use Framework\GeneralBundle\Entity\Direccion\DireccionIndustrial;

class DomicilioBinder {

	/**
	 * @var integer $id
	 *
	 */
	private $id;
	
	/**
	 *
	 * @var integer $tipo
	 */
	private $tipo;
	
	/**
	 * 
	 * @var \Framework\GeneralBundle\Entity\Localidad $localidad
	 */
	private $localidad;
	
	/**
	 *
	 * @var string $codigopostal
	 */
	private $codigopostal;

	public function getLocalidad() {
		return $this->localidad;
	}

	public function setLocalidad($localidad,$em = null) {
		$this->localidad = $localidad;
	}

	public function getCodigopostal() {
		return $this->codigopostal;
	}

	public function setCodigopostal($codigopostal) {
		$this->codigopostal = $codigopostal;
	}

	/**
	 * @var Framework\GeneralBundle\Entity\Direccion\DireccionRural $dirRural
	 *
	 */
	private $dirRural;

	/**
	 * @var Framework\GeneralBundle\Entity\Direccion\DireccionUrbano $dirUrbano
	 *
	 */
	private $dirUrbano;

	/**
	 * @var Framework\GeneralBundle\Entity\Direccion\DireccionIndustrial $dirIndustrial
	 *
	 */
	private $dirIndustrial;

	/**
	 * Get id
	 *
	 * @return integer 
	 */
	public function getDirRural() {
		return $this->dirRural;
	}

	public function setDirRural($dirRural) {
		$this->dirRural = $dirRural;
	}

	public function getDirUrbano() {
		return $this->dirUrbano;
	}

	public function setDirUrbano($dirUrbano) {
		$this->dirUrbano = $dirUrbano;
	}

	public function getDirIndustrial() {
		return $this->dirIndustrial;
	}

	public function setDirIndustrial($dirIndustrial) {
		$this->dirIndustrial = $dirIndustrial;
	}

	public function getId() {
		return $this->id;
	}

	public function getTipo() {
		return $this->tipo;
	}

	public function setTipo($tipo) {
		if (!is_null($tipo))
			$this->tipo = $tipo;
	}

	public function getDirGeneral() {
		return array('localidad' => $this->getLocalidad(), 'codigopostal' => $this->getCodigopostal());
	}

	public function setDirGeneral($dirGeneral) {
		if (is_array($dirGeneral) && array_key_exists('localidad', $dirGeneral) && array_key_exists('codigopostal', $dirGeneral)) {
			$this->setLocalidad($dirGeneral['localidad']);
			$this->setCodigopostal($dirGeneral['codigopostal']);
		}
	}

	/**
	 * Genera los datos del Tipo Domicilio 
	 * @return \Framework\GeneralBundle\Entity\Domicilio 
	 */
	public function generaDato($em) {
		$Domicilio = new Domicilio();
		switch ($this->tipo) {
			case Domicilio::TIPO_URBANO:
				$dir = $this->getDirUrbano();
				if(!is_null($dir->getCalle()) && !is_null($dir->getNumero())){
					$Domicilio->setDireccion($dir);
				}else{
					return null;
				}
				break;
			case Domicilio::TIPO_RURAL:
				$dir = $this->getDirRural();
				if(!is_null($dir->getRuta()) && !is_null($dir->getKm())){
					$Domicilio->setDireccion($dir);
				}else{
					return null;
				}
				break;
			case Domicilio::TIPO_INDUSTRIAL:
				$dir = $this->getDirIndustrial();
				if(!is_null($dir->getRuta()) && !is_null($dir->getKm())){
					$Domicilio->setDireccion($dir);
				}else{
					return null;
				}
				break;
		}
		$idlocalidad= $this->getLocalidad();
		$localidad = $em->getRepository("FrameworkGeneralBundle:Localidad")->find($idlocalidad);
		if(is_null($localidad)){
			return null;
		}
		$Domicilio->setLocalidad($localidad);
		$Domicilio->setCodPostal($this->getCodigopostal());
		$Domicilio->setTipo($this->getTipo());
		$em->persist($Domicilio);
		
		return $Domicilio;
	}

	public function __construct() {
		$this->setTipo(Domicilio::TIPO_URBANO);
	}
	
	
	public function getDireccion(){
		if (!is_null($this->dirUrbano) )		return $this->dirUrbano;
		if (!is_null($this->dirRural) )		return $this->dirRural;
		if (!is_null($this->dirIndustrial ))	return $this->dirIndustrial;
		return null;
	}
	
	public function setDireccion($direccion){
		if ($direccion instanceof DireccionUrbano )		$this->setDirUrbano ($direccion);
		if ($direccion instanceof DireccionRural)		$this->setDirRural($direccion);
		if ($direccion instanceof DireccionIndustrial)	$this->setDirIndustrial ($direccion);
		return $this;
	}
}
?>