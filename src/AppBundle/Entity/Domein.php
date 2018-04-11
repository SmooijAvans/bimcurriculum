<?php
// src/AppBundle/Entity/Task.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="domein")
 */
class Domein
{

	 /**
     * @ORM\Column(type="integer", name="did")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

	/**
	 * @ORM\Column(type="string", name="naam", length=100)
	 */
	protected $naam;

	public function getNaam()
	{
		return $this->naam;
	}

	public function setNaam($naam)
	{
		$this->naam = $naam;
	}

	public function setId($id) {$this->id = $id;}
	public function getId() {return $this->id;}

	public function __toString() {
		return $this->naam;
	}

	/**
	 * @ORM\OneToMany(targetEntity="Toets", mappedBy="domein")
	 */
	protected $toetsen;
	public function setToetsen($var) {$this->toetsen = $var;}
	public function getToetsen() {return $this->toetsen;}


	public function __construct() {
		$this->toetsen = new ArrayCollection();
	}
}

?>
