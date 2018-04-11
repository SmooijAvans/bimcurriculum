<?php
// src/AppBundle/Entity/Task.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="medewerker")
 */
class Medewerker implements UserInterface, \Serializable
{

		/**
		* @ORM\Column(name="code", type="string", length=50, unique=true)
		*/
		private $username;

		/**
		* @ORM\Column(name="rol", type="string", length=20)
		*/
		private $rol;

		public function getUsername()
		{
			return $this->username;
		}

		public function getCode() {
			return $this->username;
		}

		public function setCode($c) {
			$this->username = $c;
			return $this;
		}

		/**
		* @ORM\Column(name="wachtwoord", type="string", length=255)
		*/
		private $password;

		public function getPassword()
		{
			return $this->password;
		}

		public function setPassword($p)
		{
			$this->password = $p;
			return $this;
		}

		/**
		* Nodig voor formulier, niet geencrypte versie van wachtwoord
		* @Assert\Length(max=4096)
		*/
		private $plainPassword;
		public function setPlainPassword($password)
		{
			$this->plainPassword = $password;
		}
		public function getPlainPassword()
		{
			return $this->plainPassword;
		}

		public function eraseCredentials()
		{
		}

		public function getSalt()
		{
			return null;
		}

		public function getRol()
		{
			return $this->rol;
		}

		public function setRol($r)
		{
			$this->rol = $r;
			return $this;
		}

		public function getRoles() {
			return array($this->rol);
		}

		/** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }

	 /**
     * @ORM\Column(name="mid", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
		public function setId($id) {$this->id = $id;}
		public function getId() {return $this->id;}

	/**
     * @ORM\Column(type="string", name="volledigenaam")
     */
    protected $volledigeNaam;
	public function setVolledigeNaam($var) {$this->volledigeNaam = $var;}
	public function getVolledigeNaam() {return $this->volledigeNaam;}

	/**
	* @ORM\OneToMany(targetEntity="Toets", mappedBy="verantwoordelijke", cascade={"persist"})
  */
	protected $toetsen;
	public function setToetsen($var) {$this->toetsen = $var;}
	public function getToetsen() {return $this->toetsen;}

	/**
	* @ORM\OneToMany(targetEntity="Toets", mappedBy="reviewer")
  */
	protected $gereviewdeToetsen;
	public function setGereviewdeToetsen($var) {$this->gereviewdeToetsen = $var;}
	public function getGereviewdeToetsen() {return $this->gereviewdeToetsen;}

	/**
	* @ORM\OneToMany(targetEntity="Course", mappedBy="eigenaar")
  */
	protected $courses;
	public function setCourses($var) {$this->courses = $var;}
	public function getCourses() {return $this->courses;}

	public function __toString()
	{
		return $this->volledigeNaam;
	}

	public function __construct()
    {
        $this->toetsen = new ArrayCollection();
				$this->courses = new ArrayCollection();
				$this->rollen = new ArrayCollection();
    }
}

?>
