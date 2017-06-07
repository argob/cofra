<?php

namespace Framework\SeguridadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Framework\SeguridadBundle\Entity\UsuarioCompositeRepository")
 * @ORM\Table()
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="subtipo",type="integer")
 * @ORM\DiscriminatorMap({"1"="Usuario","2"="Grupo"})
 * @ORM\HasLifecycleCallbacks()
 */
abstract class UsuarioComposite {

	const TIPO_USUARIO = 1;
	const TIPO_GRUPO = 2;

	/**
	 * @var integer $id
	 * 
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToMany(targetEntity="Grupo", inversedBy="contenidos" )
	 */
	private $gruposPertenece;

	/**
	 * @ORM\ManyToMany(targetEntity="PermisoComposite")
	 * @ORM\JoinTable(
	 * 	joinColumns={@ORM\JoinColumn(name="usuariocomp_id", referencedColumnName="id")},
	 * 	inverseJoinColumns={@ORM\JoinColumn(name="permisocomp_id", referencedColumnName="id")})
	 */
	protected $permisos = null;

	/**
	 * @var \DateTime $created
	 * 	
	 * @ORM\Column(name="created", type="datetime")
	 */
	protected $created;

	/**
	 * @var \DateTime $updated
	 * 	
	 * @ORM\Column(name="updated", type="datetime")
	 */
	protected $updated;

	/**
	 *
	 * @return \DateTime
	 */
	public function getCreated() {
		return $this->created;
	}

	/**
	 *
	 * @param \DateTime $created 
	 */
	public function setCreated(\DateTime $created) {
		$this->created = $created;
	}

	public function getUpdated() {
		return $this->updated;
	}

	public function setUpdated($updated) {
		$this->updated = $updated;
	}

	public function __construct() {
		$this->setCreated(new \DateTime());
		$this->setUpdated(new \DateTime());
	}

	/**
	 * ORM\preUpdate
	 */
	public function setUpdatedValue() {
		$this->setUpdated(new \DateTime());
	}

	final public static function getTipoString(int $tipo) {
		switch ($tipo) {
			case static::TIPO_USUARIO:
				return "usuario";
				break;
			case static::TIPO_GRUPO:
				return "grupo";
				break;
			default: return "";
		}
	}

	final public function getGruposPertenece() {
		return $this->gruposPertenece;
	}

	public function setGruposPertenece($gruposPertenece) {
		$this->gruposPertenece = $gruposPertenece;
	}

	protected function getId() {
		return $this->id;
	}

	protected function setId($id) {
		$this->id = $id;
	}

	public function getPermisosContenidos() {
		return $this->permisos;
	}

	public function setPermisosContenidos($Permisos) {
		$this->permisos = $Permisos;
	}

	public function getPermisos() {
		return $this->permisos;
	}

	protected function setPermisos($Permisos) {
		$this->permisos = $Permisos;
	}

	protected function getAcciones() {
		$this->getPermisos();
		$this->getGruposPertenece();
		$ret = array();
		foreach ($this->permisos as $permiso) {
			/* @var $permiso PermisoComposite */
			$ret[] = $permiso->getAcciones();
		}
		foreach ($this->gruposPertenece as $grupo) {
			/* @var $grupo Grupo */
			$ret[] = $grupo->getAcciones();
		}
		if (count($ret) > 0) {
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

	abstract public function __toString();

	protected function addGrupoPertenece(Grupo $grupo) {
		if (!$this->getGruposPertenece()->contains($grupo)) {
			$this->getGruposPertenece()->add($grupo);
			$grupo->agregarUsuario($this);
		}
	}

	public function addPermiso(PermisoComposite $permiso) {
		if (!$this->getPermisos()->contains($permiso)) {
			$this->getPermisos()->add($permiso);
		}
	}

	abstract public function getNombre();

	abstract public function getTipoUC();
}

?>