<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="bronherkomst")
 */
class BronHerkomst
{

	 /**
     * @ORM\Column(type="integer", name="bhid")
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
     * @ORM\Column(type="string", name="url")
     */
    protected $url;
	public function setUrl($var) {$this->url = $var;}
	public function getUrl() {return $this->url;}

	public function __toString()
	{
		return $this->naam;
	}

	/**
	* @ORM\OneToMany(targetEntity="Bron", mappedBy="herkomst")
	*/
	protected $bronnen;

	public function setBronnen($bronnen) {$this->bronnen = $bronnen;}
	public function getBronnen() {return $this->bronnen;}

	public function __construct() {
		$this->bronnen = new ArrayCollection();
	}
}

?>
