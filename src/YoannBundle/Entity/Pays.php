<?php

namespace YoannBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Pays
 *
 * @ORM\Table(name="pays")
 * @ORM\Entity(repositoryClass="YoannBundle\Repository\PaysRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Pays
{
    const UPLOAD_DIR = __DIR__ . '/../../../web/uploads/drapeaux';

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
     * @var string
     *
     * @ORM\Column(name="drapeau", type="string", length=255)
     */
    private $drapeau;

    /**
     * @ORM\OneToMany(targetEntity="Athlete", mappedBy="pays", cascade={"remove"})
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
     * @return Pays
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
     * Set drapeau
     *
     * @param string $drapeau
     *
     * @return Pays
     */
    public function setDrapeau($drapeau)
    {
        $this->drapeau = $drapeau;

        return $this;
    }

    /**
     * Get drapeau
     *
     * @return string
     */
    public function getDrapeau()
    {
        return $this->drapeau;
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
     * Supprime automatiquement le drapeau si le pays est supprimé
     *
     * @ORM\PostRemove
     */
    public function autoDeleteDrapeau(){
      $this->deleteDrapeau($this->drapeau);
    }

    /**
     * Supprime un drapeau
     */
    public function deleteDrapeau($drapeau){
      if(file_exists(self::UPLOAD_DIR.'/'.$drapeau)){
        unlink(self::UPLOAD_DIR.'/'.$drapeau);
      }
      return true;
    }

    public function __toString(){
      return $this->nom;
    }
}
