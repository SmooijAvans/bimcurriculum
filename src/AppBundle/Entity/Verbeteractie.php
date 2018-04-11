<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="verbeteractie")
 */
class Verbeteractie
{
    
    /**
     * @ORM\Column(type="integer", name="id")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", name="actie", length=255)
     */
    private $actie;

    /**
     * @ORM\Column(type="text", name="beschrijving")
     */
    private $beschrijving;

    /**
     * @ORM\Column(type="decimal", name="uren")
     */
    private $uren;

    /**
     * @ORM\Column(type="string", name="status", length=255)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="Medewerker", inversedBy="verbeteracties")
     * @ORM\JoinColumn(name="medewerker", referencedColumnName="mid")
     */
    private $medewerker;

    /**
     * @ORM\ManyToOne(targetEntity="Toets", inversedBy="verbeteracties")
     * @ORM\JoinColumn(name="toets", referencedColumnName="tid")
     */
    private $toets;


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
     * Set actie
     *
     * @param string $actie
     *
     * @return Verbeteractie
     */
    public function setActie($actie)
    {
        $this->actie = $actie;

        return $this;
    }

    /**
     * Get actie
     *
     * @return string
     */
    public function getActie()
    {
        return $this->actie;
    }

    /**
     * Set beschrijving
     *
     * @param string $beschrijving
     *
     * @return Verbeteractie
     */
    public function setBeschrijving($beschrijving)
    {
        $this->beschrijving = $beschrijving;

        return $this;
    }

    /**
     * Get beschrijving
     *
     * @return string
     */
    public function getBeschrijving()
    {
        return $this->beschrijving;
    }

    /**
     * Set uren
     *
     * @param string $uren
     *
     * @return Verbeteractie
     */
    public function setUren($uren)
    {
        $this->uren = $uren;

        return $this;
    }

    /**
     * Get uren
     *
     * @return string
     */
    public function getUren()
    {
        return $this->uren;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Verbeteractie
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set medewerker
     *
     * @param integer $medewerker
     *
     * @return Verbeteractie
     */
    public function setMedewerker($medewerker)
    {
        $this->medewerker = $medewerker;

        return $this;
    }

    /**
     * Get medewerker
     *
     * @return int
     */
    public function getMedewerker()
    {
        return $this->medewerker;
    }

    /**
     * Set toets
     *
     * @param integer $toets
     *
     * @return Verbeteractie
     */
    public function setToets($toets)
    {
        $this->toets = $toets;

        return $this;
    }

    /**
     * Get toets
     *
     * @return int
     */
    public function getToets()
    {
        return $this->toets;
    }
}

