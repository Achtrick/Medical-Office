<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mutuelle
 *
 * @ORM\Table(name="mutuelle")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MutuelleRepository")
 */
class Mutuelle
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="cin", type="string", length=255)
     */
    private $cin;

    /**
     * @var string
     *
     * @ORM\Column(name="valide", type="string", length=255)
     */
    private $valide;

    /**
     * @var string
     *
     * @ORM\Column(name="a", type="string", length=255)
     */
    private $a;

    /**
     * @var string
     *
     * @ORM\Column(name="identificateur", type="string", length=255)
     */
    private $identificateur;

    /**
     * @var string
     *
     * @ORM\Column(name="caisse", type="string", length=255)
     */
    private $caisse;


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
     * Set nom
     *
     * @param string $nom
     *
     * @return Mutuelle
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Mutuelle
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set cin
     *
     * @param string $cin
     *
     * @return Mutuelle
     */
    public function setCin($cin)
    {
        $this->cin = $cin;

        return $this;
    }

    /**
     * Get cin
     *
     * @return string
     */
    public function getCin()
    {
        return $this->cin;
    }

    /**
     * Set valide
     *
     * @param string $valide
     *
     * @return Mutuelle
     */
    public function setValide($valide)
    {
        $this->valide = $valide;

        return $this;
    }

    /**
     * Get valide
     *
     * @return string
     */
    public function getValide()
    {
        return $this->valide;
    }

    /**
     * Set a
     *
     * @param string $a
     *
     * @return Mutuelle
     */
    public function setA($a)
    {
        $this->a = $a;

        return $this;
    }

    /**
     * Get a
     *
     * @return string
     */
    public function getA()
    {
        return $this->a;
    }

    /**
     * Set identificateur
     *
     * @param string $identificateur
     *
     * @return Mutuelle
     */
    public function setIdentificateur($identificateur)
    {
        $this->identificateur = $identificateur;

        return $this;
    }

    /**
     * Get identificateur
     *
     * @return string
     */
    public function getIdentificateur()
    {
        return $this->identificateur;
    }

    /**
     * Set caisse
     *
     * @param string $caisse
     *
     * @return Mutuelle
     */
    public function setCaisse($caisse)
    {
        $this->caisse = $caisse;

        return $this;
    }

    /**
     * Get caisse
     *
     * @return string
     */
    public function getCaisse()
    {
        return $this->caisse;
    }
}

