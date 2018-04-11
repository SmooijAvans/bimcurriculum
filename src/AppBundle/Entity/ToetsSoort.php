<?php
// src/AppBundle/Entity/Task.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="toetssoort")
 */
class ToetsSoort
{

	 /**
     * @ORM\Column(type="integer", name="sid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

	 /**
     * @ORM\Column(type="string", name="naam")
     */
    protected $naam;

		/**
		* @ORM\OneToMany(targetEntity="Toets", mappedBy="soort")
	  */
		protected $toetsen;
		public function getToetsen() {
			return $this->toetsen;
		}
		public function setToetsen($t) {$this->toetsen = $t;}

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getNaam()
	{
		return $this->naam;
	}

	public function setNaam($naam)
	{
		$this->naam = $naam;
	}

	public function __toString() {
		return $this->naam;
	}
}

?>
