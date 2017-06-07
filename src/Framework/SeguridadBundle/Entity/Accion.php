<?php

namespace Framework\SeguridadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * Framework\SeguridadBundle\Entity\Accion
 *
 * @ORM\Entity()
 */
class Accion extends PermisoComposite implements RoleInterface {

	public function getRole() {
		$esta = strpos($this->getNombre(), "ROLE_");
		$testo = str_replace(" ", "_", strtoupper(($esta === 0) ? $this->getNombre() : "ROLE_{$this->getNombre()}"));
		return $testo;
	}

	public function getAcciones() {
		return array($this);
	}

	public function getTipo() {
		return static::TIPO_ACCION;
	}

	public function __construct() {
		parent::__construct();
	}

}