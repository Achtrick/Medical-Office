<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RDV
 *
 * @ORM\Table(name="r_d_v")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RDVRepository")
 */
class RDV
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
     * @ORM\Column(name="cin", type="string", unique=false)
     */
    private $cin;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="seance", type="string", length=255)
     */
    private $seance;

    /**
     * @var int
     *
     * @ORM\Column(name="num_tel", type="integer", unique=false)
     */
    private $numTel;

    /**
     * @var string
     *
     * @ORM\Column(name="date_rdv", type="string", length=255)
     */
    private $date_rdv;


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
     * Set numTel
     *
     * @param integer $numTel
     *
     * @return RDV
     */
    public function setNumTel($numTel)
    {
        $this->numTel = $numTel;

        return $this;
    }

    /**
     * Get numTel
     *
     * @return int
     */
    public function getNumTel()
    {
        return $this->numTel;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return RDV
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
     * Set nom
     *
     * @param string $nom
     *
     * @return RDV
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
     * Set seance
     *
     * @param string $seance
     *
     * @return RDV
     */
    public function setSeance($seance)
    {
        $this->seance = $seance;

        return $this;
    }

    /**
     * Get seance
     *
     * @return string
     */
    public function getSeance()
    {
        return $this->seance;
    }

    /**
     * Set dateRdv
     *
     * @param string $dateRdv
     *
     * @return RDV
     */
    public function setDateRdv($dateRdv)
    {
        $this->date_rdv = $dateRdv;

        return $this;
    }

    /**
     * Get dateRdv
     *
     * @return string
     */
    public function getDateRdv()
    {
        return $this->date_rdv;
    }

    /**
     * Set cin
     *
     * @param string $cin
     *
     * @return RDV
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
}
