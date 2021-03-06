<?php

namespace YoannBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Discipline
 *
 * @ORM\Table(name="discipline")
 * @ORM\Entity(repositoryClass="YoannBundle\Repository\DisciplineRepository")
 */
class Discipline
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
     * @ORM\Column(name="nom", type="string", length=255, unique=true)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="Athlete", mappedBy="discipline", cascade={"remove"})
     */
    private $athletes;

    public function __construct() {
      $this->athletes = new ArrayCollection();
    }


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
     * @return Discipline
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

    public function __toString(){
      return $this->nom;
    }
}
