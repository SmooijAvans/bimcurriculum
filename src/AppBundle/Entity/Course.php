<?php
// src/AppBundle/Entity/Task.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="course")
 */
class Course
{

	 /**
     * @ORM\Column(type="integer", name="cid")
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
     * @ORM\Column(type="string", name="voltijddeeltijd")
     */
    protected $voltijddeeltijd;
	public function setVoltijddeeltijd($var) {$this->voltijddeeltijd = $var;}
	public function getVoltijddeeltijd() {return $this->voltijddeeltijd;}

	/**
     * @ORM\Column(type="integer", name="jaar")
     */
    protected $jaar;
	public function setJaar($var) {$this->jaar = $var;}
	public function getJaar() {return $this->jaar;}

	/**
     * @ORM\Column(type="text", name="beschrijving")
     */
    protected $beschrijving;
	public function setBeschrijving($var) {$this->beschrijving = $var;}
	public function getBeschrijving() {return $this->beschrijving;}

	/**
     * @ORM\Column(type="integer", name="periode")
     */
    protected $periode;
	public function setPeriode($var) {$this->periode = $var;}
	public function getPeriode() {return $this->periode;}

	/**
     * @ORM\ManyToOne(targetEntity="Medewerker", inversedBy="courses")
     * @ORM\JoinColumn(name="eigenaar", referencedColumnName="mid")
     */
    protected $eigenaar;
	public function setEigenaar($var) {$this->eigenaar = $var;}
	public function getEigenaar() {return $this->eigenaar;}

	/**
	 * @ORM\OneToMany(targetEntity="Toets", mappedBy="course")
	 */
	protected $toetsen;
	public function setToetsen($var) {$this->toetsen = $var;}
	public function getToetsen() {return $this->toetsen;}

	public function __construct()
    {
        $this->leerdoelen = new ArrayCollection();
		$this->toetsen = new ArrayCollection();
    }

	public function __toString() {
		return "Course: " . $this->naam;
	}
}

?>
