<?php

namespace Framework\SeguridadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Framework\SeguridadBundle\Entity\Funcionalidad
 *
 * @ORM\Entity()
 */
class Funcionalidad extends PermisoComposite {

	/**
	 * 
	 * ORM\ManyToMany(targetEntity="PermisoComposite", mappedBy="funcionalidadesPertenece", cascade={"persist"})
	 *
	 * @ORM\ManyToMany(targetEntity="PermisoComposite", inversedBy="funcionalidadesPertenece")
	 * @ORM\JoinTable(name="permisocomposite_funcionalidad",
	 *      joinColumns={@ORM\JoinColumn(name="funcionalidad_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="permisocomposite_id", referencedColumnName="id")}
	 *      )
	 */
	private $permisosContenidos;

	/**
	 * Get subfuncionalidades
	 * 
	 * @return array
	 */
	public function getPermisosContenidos() {
		return $this->permisosContenidos;
	}

	public function __construct() {
		parent::__construct();
		$this->permisosContenidos = new ArrayCollection();
	}

	/**
	 *
	 * @return array Accion
	 */
	public function getAcciones() {
		$Acciones = $this->getPermisosContenidos();
		$ret = array();
		if (count($Acciones) > 0) {
			foreach ($Acciones as $PermisoComposite) {
				/* @var $PermisoComposite PermisoComposite */
				$ret[] = $PermisoComposite->getAcciones();
			}
			while (count($ret) > 1) {
				$ret[] = array_merge($ret[0], $ret[1]);
				array_shift($ret);
				array_shift($ret);
			}
			return array_unique($ret[0]);
		}
		else
			return array();
	}

	/**
	 * @todo: verificar que no se pueda agregar de forma que se cuelgue en recursion
	 * @param array PermisoComposite $permisosContenidos 
	 */
	public function setPermisosContenidos($permisosContenidos) {
		$this->permisosContenidos = $permisosContenidos;
//		foreach ($this->permisosContenidos as $permisoComposite) {
//			/* @var $permisoComposite PermisoComposite */
//			$permisoComposite->addFuncionalidadPertenece($this);
//		}
	}

	/**
	 * @todo: verificar que no se pueda agregar de forma que se cuelgue en recursion
	 * @param PermisoComposite $permiso
	 */
	public function addPermisoContenido(PermisoComposite $permiso) {
		if (!$this->getPermisosContenidos()->contains($permiso)) {
			$this->getPermisosContenidos()->add($permiso);
			$permiso->addFuncionalidadPertenece($this);
		}
	}

	public function getTipo() {
		return static::TIPO_FUNCIONALIDAD;
	}

}