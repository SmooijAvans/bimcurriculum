<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="bron")
 */
class Bron
{

	 /**
     * @ORM\Column(type="integer", name="bid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
	public function setId($id) {$this->id = $id;}
	public function getId() {return $this->id;}

	 /**
     * @ORM\Column(type="string", name="naam")
     */
    protected $naam;
	public function setNaam($var) {$this->naam = $var;}
	public function getNaam() {return $this->naam;}

	/**
	* @ORM\ManyToOne(targetEntity="BronHerkomst", inversedBy="bronnen")
	* @ORM\JoinColumn(name="herkomst", referencedColumnName="bhid")
	*/
	protected $herkomst;
	public function setHerkomst($var) {$this->herkomst = $var;}
	public function getHerkomst() {return $this->herkomst;}

	public function __toString() {
		return $this->naam;
	}

	/**
	* @ORM\OneToMany(targetEntity="Leerdoel", mappedBy="bron")
	*/
	protected $leerdoelen;
	public function setLeerdoelen($leerdoelen) {$this->leerdoelen = $leerdoelen;}
	public function getLeerdoelen() {return $this->leerdoelen;}

	public function __construct()
	{
		$this->leerdoelen = new ArrayCollection();
	}

}

?>
