<?php
// src/AppBundle/Entity/Task.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="literatuur")
 */
class Literatuur
{

	/**
		* @ORM\Column(type="integer", name="ltid")
		* @ORM\Id
		* @ORM\GeneratedValue(strategy="AUTO")
	*/
  protected $id;

	/**
		* @ORM\Column(type="string", name="auteur")
	*/
	protected $auteur;

	/**
		* @ORM\Column(type="string", name="titel")
		*/
	protected $titel;

	/**
		* @ORM\Column(type="string", name="identificatie")
		*/
	protected $identificatie;

	/**
		* @ORM\Column(type="string", name="uitgever")
		*/
	protected $uitgever;

	/**
		* @ORM\Column(type="string", name="jaartal")
		*/
	protected $jaartal;

	/**
		* @ORM\Column(type="string", name="druk")
		*/
	protected $druk;

	/**
	 * @ORM\OneToMany(targetEntity="Toetsstof", mappedBy="literatuur")
	 */
	protected $toetsstof;
	public function getToetsstof(){return $this->toetsstof;}
	public function setToetsstof($stof) { $this->toetsstof = $stof; }

	public function getId(){
			return $this->id;
		}

		public function setId($id){
			$this->id = $id;
		}

		public function getAuteur(){
			return $this->auteur;
		}

		public function setAuteur($auteur){
			$this->auteur = $auteur;
		}

		public function getTitel(){
			return $this->titel;
		}

		public function setTitel($titel){
			$this->titel = $titel;
		}

		public function getIdentificatie(){
			return $this->identificatie;
		}

		public function setIdentificatie($identificatie){
			$this->identificatie = $identificatie;
		}

		public function getUitgever(){
			return $this->uitgever;
		}

		public function setUitgever($uitgever){
			$this->uitgever = $uitgever;
		}

		public function getJaartal(){
			return $this->jaartal;
		}

		public function setJaartal($jaartal){
			$this->jaartal = $jaartal;
		}

		public function getDruk(){
			return $this->druk;
		}

		public function setDruk($druk){
			$this->druk = $druk;
		}

		public function getDisplayValue() {
			return $this->auteur . ": " . $this->titel;
		}

		public function __toString() {
			return $this->auteur . ": " . $this->titel;
		}
}

?>
