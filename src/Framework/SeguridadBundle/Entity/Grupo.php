<?php

namespace Framework\SeguridadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Framework\SeguridadBundle\Entity\Grupo
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Framework\SeguridadBundle\Entity\GrupoRepository")
 */
class Grupo extends UsuarioComposite {

	/**
	 * @var integer $id
	 *
	 * @ORM\Id
	 */
	protected $id;

	/**
	 * @var string $nombre
	 *
	 * @ORM\Column(name="nombre", type="string", length=255)
	 */
	private $nombre;

	/**
	 * @var UsuarioComposite[] $contenidos
	 * 
	 * @ORM\ManyToMany(targetEntity="UsuarioComposite", mappedBy="gruposPertenece")
	 */
	private $contenidos;

	/**
	 * Get id
	 *
	 * @return integer 
	 */
	public function getId() {
		return $this->id;
	}

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

	/**
	 * Get contenidos
	 * 
	 * @return UsuarioComposite[]
	 */
	public function getContenidos() {
		return $this->contenidos;
	}

	/**
	 *
	 * @return UsuarioComposite[]
	 */
	public function getContenidosRecursivo() {
		$contenidos = $this->getContenidos();
		$ret = array();
		if (count($contenidos) > 0) {
			foreach ($contenidos as $UC) {
				/* @var $UC UsuarioComposite */
				$ret[] = array($UC);
				if ($UC->getTipoUC() == static::TIPO_GRUPO) {
					$ret[] = $UC->getContenidosRecursivo();
				}
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
	 * Agrega un UsuarioComposite existente al grupo
	 * Devuelve true si el UsuarioComposite existe y no genera problemas de recursion infinita
	 * 
	 * @param UsuarioComposite $usuario
	 * 
	 * @return boolean
	 */
	public function agregarUsuario(UsuarioComposite $usuario) {
		if (!$this->getContenidos()->contains($usuario)) {
			$this->getContenidos()->add($usuario);
			$usuario->addGrupoPertenece($this);
		}
	}

	public function __toString() {
		return $this->getNombre();
	}

	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @todo: verificar que no se pueda agregar de forma que se cuelgue en recursion
	 * @param array UsuarioComposite $contenidos 
	 */
	public function setContenidos($contenidos) {
		$this->contenidos = $contenidos;
		foreach ($this->contenidos as $ucomp) {
			/* @var UsuarioComposite $ucomp */
			$ucomp->addGrupoPertenece($this);
		}
	}

	/**
	 *
	 * @return integer
	 */
	public function getTipoUC() {
		return static::TIPO_GRUPO;
	}

}