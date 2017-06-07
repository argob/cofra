<?php

namespace Framework\SeguridadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="subtipo",type="integer")
 * @ORM\DiscriminatorMap({"1"="Accion","2"="Funcionalidad"})
 */
abstract class PermisoComposite {

	const TIPO_ACCION = 1;
	const TIPO_FUNCIONALIDAD = 2;

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @var string $nombre
	 *
	 * @ORM\Column(name="nombre", type="string", length=255, unique=true)
	 */
	protected $nombre;

	/**
	 * @var array $funcionalidadesPertenece
	 *
	 * ORM\ManyToMany(targetEntity="Funcionalidad", inversedBy="permisosContenidos", cascade={"persist"})
	 * @ORM\ManyToMany(targetEntity="Funcionalidad", mappedBy="permisosContenidos")
	 */
	private $funcionalidadesPertenece;

	/**
	 * Set nombre
	 *
	 * @param string $nombre
	 */
	public function setNombre($nombre) {
		$this->nombre = $nombre;
	}

	/**
	 * Get nombre
	 *
	 * @return string 
	 */
	public function getNombre() {
		return $this->nombre;
	}

	public function __construct() {
		$this->funcionalidadesPertenece = new ArrayCollection();
	}

	/**
	 * Get funcionalidadesPertenece
	 * 
	 * @return array
	 */
	public function getFuncionalidadesPertenece() {
		return $this->funcionalidadesPertenece;
	}

	public function setFuncionalidadesPertenece($funcionalidadesPertenece) {
		$this->funcionalidadesPertenece = $funcionalidadesPertenece;
	}

	abstract public function getAcciones();

	public function getId() {
		return $this->id;
	}

	public function __toString() {
		return $this->getNombre();
	}

	protected function addFuncionalidadPertenece(Funcionalidad $funcionalidad) {
		if (!$this->getFuncionalidadesPertenece()->contains($funcionalidad)) {
			$this->getFuncionalidadesPertenece()->add($funcionalidad);
			$funcionalidad->addPermisoContenido($this);
		}
	}

	abstract public function getTipo();
}

?>