<?php

namespace Framework\SeguridadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Framework\SeguridadBundle\Entity\UsuarioComposite;
use Symfony\Component\Security\Core\Role\Role;

/**
 * Framework\SeguridadBundle\Entity\Usuario
 *
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="username_idx", columns={"username"})})
 * @ORM\Entity(repositoryClass="Framework\SeguridadBundle\Entity\UsuarioRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="subtipo",type="integer")
 * @ORM\DiscriminatorMap({"1"="Usuario","2"="\Magyp\RendicionDeCajaBundle\Entity\Usuario"})
 */
class Usuario extends UsuarioComposite implements AdvancedUserInterface {

	/**
	 * @var integer $id
	 *
	 * @ORM\Id
	 */
	protected $id;

	/**
	 * @var string $username
	 *
	 * @ORM\Column(name="username", type="string", length=255)
	 */
	protected $username;

	/**
	 * @var string $password
	 *
	 * @ORM\Column(name="password", type="string", length=255)
	 */
	protected $password;

	/**
	 * @var string $salt
	 *
	 * @ORM\Column(name="salt", type="string", length=32)
	 */
	protected $salt;

	/**
	 * @var string $email
	 *
	 * @ORM\Column(name="email", type="string", length=255)
	 */
	protected $email;

	/**
	 * @var boolean $activo
	 *
	 * @ORM\Column(name="activo", type="boolean")
	 */
	protected $activo;

	/**
	 * @var date $fechaExpiracionCuenta
	 *
	 * @ORM\Column(name="fechaExpiracionCuenta", type="date", nullable=true)
	 */
	protected $fechaExpiracionCuenta;

	/**
	 * @var date $fechaExpiracionCredenciales
	 *
	 * @ORM\Column(name="fechaExpiracionCredenciales", type="date",nullable=true)
	 */
	protected $fechaExpiracionCredenciales;

	/**
	 * @var boolean $bloqueada
	 *
	 * @ORM\Column(name="bloqueada", type="boolean")
	 */
	protected $bloqueada;

	/**
	 *
	 * @var array
	 */
	protected $roles = null;

	public function __construct() {
		parent::__construct();
		$this->activo = true;
		$this->bloqueada = false;
		$this->salt = md5(uniqid(null, true));
		$this->fechaExpiracionCredenciales = null;
		$this->fechaExpiracionCuenta = null;
	}

	/**
	 * Get id
	 *
	 * @return integer 
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set username
	 *
	 * @param string $username
	 */
	public function setUsername($username) {
		$this->username = $username;
	}

	/**
	 * Get username
	 *
	 * @return string 
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * Set password
	 *
	 * @param string $password
	 */
	public function setPassword($password) {
		$this->password = $password;
	}

	/**
	 * Get password
	 *
	 * @return string 
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * Set salt
	 *
	 * @param string $salt
	 */
	public function setSalt($salt) {
		$this->salt = $salt;
	}

	/**
	 * Get salt
	 *
	 * @return string 
	 */
	public function getSalt() {
		return $this->salt;
	}

	/**
	 * Set email
	 *
	 * @param string $email
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

	/**
	 * Get email
	 *
	 * @return string 
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * Set fechaExpiracionCuenta
	 *
	 * @param date $fechaExpiracionCuenta
	 */
	public function setFechaExpiracionCuenta($fechaExpiracionCuenta) {
		$this->fechaExpiracionCuenta = $fechaExpiracionCuenta;
	}

	/**
	 * Get fechaExpiracionCuenta
	 *
	 * @return date 
	 */
	public function getFechaExpiracionCuenta() {
		return $this->fechaExpiracionCuenta;
	}

	/**
	 * Set fechaExpiracionCredenciales
	 *
	 * @param date $fechaExpiracionCredenciales
	 */
	public function setFechaExpiracionCredenciales($fechaExpiracionCredenciales) {
		$this->fechaExpiracionCredenciales = $fechaExpiracionCredenciales;
	}

	/**
	 * Get fechaExpiracionCredenciales
	 *
	 * @return date 
	 */
	public function getFechaExpiracionCredenciales() {
		return $this->fechaExpiracionCredenciales;
	}

	/**
	 * Elimina en memoria los datos sensibles del usuario cargado para que no se acceda ni siquiera por error
	 */
	public function eraseCredentials() {
		//$this->password = null;
		//$this->salt = null;
	}

	/**
	 *
	 * @return array
	 */
	public function getRoles() {
		if (is_null($this->roles)) {
			$this->roles = array_merge(array(new Role("ROLE_USER")), $this->getAcciones());
		}
		return $this->roles;
	}

	/**
	 *
	 * @return boolean 
	 */
	public function isAccountNonExpired() {
		return true;
	}

	/**
	 *
	 * @return boolean 
	 */
	public function isAccountNonLocked() {
		return !$this->getBloqueada();
	}

	/**
	 *
	 * @return boolean 
	 */
	public function isCredentialsNonExpired() {
		return true;
	}

	/**
	 *
	 * @return boolean
	 */
	public function isEnabled() {
		//return activo && not locked && not expired
		return $this->getActivo() && $this->isAccountNonLocked() && $this->isAccountNonExpired();
	}

	/**
	 *
	 * @param UserInterface $user
	 * @return boolean 
	 */
	public function equals(UserInterface $user) {
		return $this->getUsername() === $user->getUsername();
	}

	/**
	 *
	 * @return boolean
	 */
	public function getActivo() {
		return $this->activo;
	}

	/**
	 *
	 * @return boolean
	 */
	public function getBloqueada() {
		return $this->bloqueada;
	}

	/**
	 * @param boolean $activo
	 */
	public function setActivo($activo) {
		$this->activo = $activo;
	}

	/**
	 * @param boolean $bloqueada
	 */
	public function setBloqueada($bloqueada) {
		$this->bloqueada = $bloqueada;
	}

	public function __toString() {
		return $this->getUsername();
	}

	public function getNombre() {
		return $this->getUsername();
	}

	public function getTipoUC() {
		return static::TIPO_USUARIO;
	}

}