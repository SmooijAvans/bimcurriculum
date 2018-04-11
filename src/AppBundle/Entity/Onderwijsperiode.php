<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="onderwijsperiode")
 */
class Onderwijsperiode
{
    
    /**
     * @ORM\Column(type="integer", name="id")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", name="startjaar")
     */
    private $startjaar;

    /**
     * @ORM\Column(type="string", name="collegejaar", length=10)
     */
    private $collegejaar;

    /**
     * @ORM\Column(type="integer", name="periode")
     */
    private $periode;

    /**
     * @ORM\Column(type="string", name="periodetype", length=20)
     */
    private $periodetype;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set startjaar
     *
     * @param integer $startjaar
     *
     * @return Onderwijsperiode
     */
    public function setStartjaar($startjaar)
    {
        $this->startjaar = $startjaar;

        return $this;
    }

    /**
     * Get startjaar
     *
     * @return int
     */
    public function getStartjaar()
    {
        return $this->startjaar;
    }

    /**
     * Set collegejaar
     *
     * @param string $collegejaar
     *
     * @return Onderwijsperiode
     */
    public function setCollegejaar($collegejaar)
    {
        $this->collegejaar = $collegejaar;

        return $this;
    }

    /**
     * Get collegejaar
     *
     * @return string
     */
    public function getCollegejaar()
    {
        return $this->collegejaar;
    }

    /**
     * Set periode
     *
     * @param integer $periode
     *
     * @return Onderwijsperiode
     */
    public function setPeriode($periode)
    {
        $this->periode = $periode;

        return $this;
    }

    /**
     * Get periode
     *
     * @return int
     */
    public function getPeriode()
    {
        return $this->periode;
    }

    /**
     * Set periodetype
     *
     * @param string $periodetype
     *
     * @return Onderwijsperiode
     */
    public function setPeriodetype($periodetype)
    {
        $this->periodetype = $periodetype;

        return $this;
    }

    /**
     * Get periodetype
     *
     * @return string
     */
    public function getPeriodetype()
    {
        return $this->periodetype;
    }

    public function __toString() {
        return $this->collegejaar . ", periode " . $this->periode . " (" . $this->periodetype . ")";
    }
}

