<?php

namespace YoannBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Athlete
 *
 * @ORM\Table(name="athlete")
 * @ORM\Entity(repositoryClass="YoannBundle\Repository\AthleteRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Athlete
{
    const UPLOAD_DIR = __DIR__ . '/../../../web/uploads/athletes';

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
     * @var \DateTime
     *
     * @ORM\Column(name="date_naissance", type="datetime")
     */
    private $dateNaissance;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255)
     */
    private $photo;

    /**
     * @ORM\ManyToOne(targetEntity="Pays", inversedBy="athletes")
     * @ORM\JoinColumn(name="pays_id", referencedColumnName="id")
     */
    private $pays;

    /**
     * @ORM\ManyToOne(targetEntity="Discipline", inversedBy="athletes")
     * @ORM\JoinColumn(name="discipline_id", referencedColumnName="id")
     */
    private $discipline;


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
     * @return Athlete
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
     * @return Athlete
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
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     *
     * @return Athlete
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return Athlete
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set pays
     *
     * @param \YoannBundle\Entity\Pays $pays
     *
     * @return Athlete
     */
    public function setPays($pays)
    {
      $this->pays = $pays;
      return $this;
    }

    /**
     * Get pays
     *
     * @return \YoannBundle\Entity\Pays
     */
    public function getPays()
    {
      return $this->pays;
    }

    /**
     * Set discipline
     *
     * @param \YoannBundle\Entity\Discipline $discipline
     *
     * @return Athlete
     */
    public function setDiscipline($discipline)
    {
      $this->discipline = $discipline;
      return $this;
    }

    /**
     * Get discipline
     *
     * @return \YoannBundle\Entity\Discipline
     */
    public function getDiscipline()
    {
      return $this->discipline;
    }

    /**
     * Get upload directory
     *
     * @return string
     */
    public function getUploadDir(){
      return self::UPLOAD_DIR;
    }

    /**
     * Supprime automatiquement la photo si l'athlète est supprimé
     *
     * @ORM\PostRemove
     */
    public function autoDeletePhoto(){
      $this->deletePhoto($this->photo);
    }

    /**
     * Supprime une photo
     */
    public function deletePhoto($photo){
      if(file_exists(self::UPLOAD_DIR.'/'.$photo)){
        unlink(self::UPLOAD_DIR.'/'.$photo);
      }
      return true;
    }
}
