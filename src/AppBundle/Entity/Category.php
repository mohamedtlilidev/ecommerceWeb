<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Exclude;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Category
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    /**
     * @var string
     *
     * @ORM\Column(name="name_fr", type="string", length=255, nullable=false)
     */
    private $nameFr;
    /**
     * @var string
     *
     * @ORM\Column(name="name_en", type="string", length=255, nullable=false)
     */
    private $nameEn;
    /**
     * @var string
     *
     * @ORM\Column(name="bg_picture", type="string", length=255, nullable=true)
     * @Assert\File(maxSize = "5M",maxSizeMessage = "Max size of file is 5MB.",mimeTypesMessage = "There are only allowed jpeg, gif, png and tiff images",mimeTypes={ "image/png","image/jpeg","image/jpg" })
     */
    private $bgPicture = '';
    /**
     * @var integer
     *
     * @ORM\Column(name="rank", type="integer", nullable=false)
     */
    private $rank;
    /**
     * @var boolean
     * @Exclude
     * @ORM\Column(name="is_published", type="boolean", nullable=false)
     */
    private $isPublished;
    /**
     * @var \DateTime
     * @Exclude
     * @ORM\Column(name="created_on", type="datetime", nullable=false)
     */
    private $createdOn;
    /**
     * @var \DateTime
     * @Exclude
     * @ORM\Column(name="updated_on", type="datetime", nullable=true)
     */
    private $updatedOn;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Article", mappedBy="category", fetch="EXTRA_LAZY")
     */
    private $articles;


    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->setUpdatedOn(new \DateTime());
        $this->setCreatedOn(new \DateTime());

    } // Notez le « s », une categorie est liée à plusieurs sous categories

    /**
     * @ORM\PreUpdate
     */
    public function PreUpdate()
    {
        $this->setUpdatedOn(new \DateTime());


    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nameFr
     *
     * @param string $nameFr
     *
     * @return Category
     */
    public function setNameFr($nameFr)
    {
        $this->nameFr = $nameFr;

        return $this;
    }

    /**
     * Get nameFr
     *
     * @return string
     */
    public function getNameFr()
    {
        return $this->nameFr;
    }

    /**
     * Set nameEn
     *
     * @param string $nameEn
     *
     * @return Category
     */
    public function setNameEn($nameEn)
    {
        $this->nameEn = $nameEn;

        return $this;
    }

    /**
     * Get nameEn
     *
     * @return string
     */
    public function getNameEn()
    {
        return $this->nameEn;
    }

    /**
     * Set bgPicture
     *
     * @param string $bgPicture
     *
     * @return Category
     */
    public function setBgPicture($bgPicture)
    {
        $this->bgPicture = $bgPicture;

        return $this;
    }

    /**
     * Get bgPicture
     *
     * @return string
     */
    public function getBgPicture()
    {
        return $this->bgPicture;
    }

    /**
     * Set rank
     *
     * @param integer $rank
     *
     * @return Category
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Get rank
     *
     * @return integer
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Set isPublished
     *
     * @param boolean $isPublished
     *
     * @return Category
     */
    public function setIsPublished($isPublished)
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    /**
     * Get isPublished
     *
     * @return boolean
     */
    public function getIsPublished()
    {
        return $this->isPublished;
    }

    /**
     * Set createdOn
     *
     * @param \DateTime $createdOn
     *
     * @return Category
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    /**
     * Get createdOn
     *
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * Set updatedOn
     *
     * @param \DateTime $updatedOn
     *
     * @return Category
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;

        return $this;
    }

    /**
     * Get updatedOn
     *
     * @return \DateTime
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * Add article
     *
     * @param \AppBundle\Entity\Article $article
     *
     * @return Category
     */
    public function addArticle(\AppBundle\Entity\Article $article)
    {
        $this->articles[] = $article;

        return $this;
    }

    /**
     * Remove article
     *
     * @param \AppBundle\Entity\Article $article
     */
    public function removeArticle(\AppBundle\Entity\Article $article)
    {
        $this->articles->removeElement($article);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * @return mixed
     *
     */
    public function __toString()
    {
        return $this->nameFr;
    }

}
