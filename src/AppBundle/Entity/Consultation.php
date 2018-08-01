<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Consultation
 *
 * @ORM\Table(name="consultation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ConsultationRepository")
 */
class Consultation
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
     * @ORM\Column(name="date_consult", type="string", length=255)
     */
    private $dateConsult;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="maladie", type="string", length=255)
     */
    private $maladie;

    /**
     * @var string
     *
     * @ORM\Column(name="cin", type="string", length=255)
     */
    private $cin;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=2555)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="file", type="string", length=255)
     */
    private $file;


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
     * Set dateConsult
     *
     * @param string $dateConsult
     *
     * @return Consultation
     */
    public function setDateConsult($dateConsult)
    {
        $this->dateConsult = $dateConsult;

        return $this;
    }

    /**
     * Get dateConsult
     *
     * @return string
     */
    public function getDateConsult()
    {
        return $this->dateConsult;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Consultation
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set maladie
     *
     * @param string $maladie
     *
     * @return Consultation
     */
    public function setMaladie($maladie)
    {
        $this->maladie = $maladie;

        return $this;
    }

    /**
     * Get maladie
     *
     * @return string
     */
    public function getMaladie()
    {
        return $this->maladie;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Consultation
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set file
     *
     * @param string $file
     *
     * @return Consultation
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set cin
     *
     * @param string $cin
     *
     * @return Consultation
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
