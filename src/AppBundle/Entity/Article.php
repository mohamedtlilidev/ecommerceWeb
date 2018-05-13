<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Exclude;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Article
 *
 * @ORM\Table(name="article", indexes={@ORM\Index(name="family_category_id", columns={"category_id"}), @ORM\Index(name="brand_id", columns={"brand_id"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Article
{
    /**
     * Article constructor.
     */
    public function __construct() {
        $this->setCreatedOn(new \DateTime());
        $this->setUpdatedOn(new \DateTime());
    }
    /**
    * @ORM\PreUpdate
    */
    public function PreUpdate() {
        $this->setUpdatedOn(new \DateTime());


    }
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
     * @ORM\Column(name="code", type="string", length=25, nullable=false)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;


    /**
     * @var string
     *
     * @ORM\Column(name="html_name", type="string", length=255, nullable=false)
     */
    private $htmlName;

    /**
     * @var string
     *
     * @ORM\Column(name="html_name_en", type="string", length=255, nullable=true)
     */
    private $htmlNameEn;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", precision=10, scale=0, nullable=false)
     */
    private $price;

    /**
     * @var integer
     *
     * @ORM\Column(name="solde", type="integer", nullable=true,options={"default"=0})
     */
    private $solde;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_offer", type="boolean", nullable=false)
     */
    private $isOffer;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_special", type="boolean", nullable=false)
     */
    private $isSpecial;



    /**
     * @var boolean
     *
     * @ORM\Column(name="is_new", type="boolean", nullable=false)
     */
    private $isNew;

    /**
     * @var string
     *
     * @ORM\Column(name="url_picture", type="string", length=255, nullable=false)
     * @Assert\File(maxSize = "5M",maxSizeMessage = "Max size of file is 5MB.",mimeTypesMessage = "There are only allowed jpeg, gif and png images",mimeTypes={ "image/png","image/jpeg","image/jpg" })
     */
    private $urlPicture;

    /**
     * @var boolean
     * @Exclude
     * @ORM\Column(name="is_published", type="boolean", nullable=false)
     */
    private $isPublished;

    /**
     * @var integer
     *
     * @ORM\Column(name="rank", type="integer", nullable=false)
     */
    private $rank;

    /**
     * @var integer
     * @Exclude
     * @ORM\Column(name="created_by", type="integer", nullable=true)
     */
    private $createdBy;

    /**
     * @var \DateTime
     * @Exclude
     * @ORM\Column(name="created_on", type="datetime",options={"default"="CURRENT_TIMESTAMP"}, nullable=false)
     */
    private $createdOn;

    /**
     * @var \DateTime
     * @Exclude
     * @ORM\Column(name="updated_on", type="datetime", nullable=true)
     */
    private $updatedOn;

    /**
     * @var \Brand
     *
     * @ORM\ManyToOne(targetEntity="Brand")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     * })
     */
    private $brand;


    /**
     * @var \Category
     *@Exclude
     * @ORM\ManyToOne(targetEntity="Category",inversedBy="articles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * })
     */
    private $category;


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
     * Set code
     *
     * @param string $code
     *
     * @return Article
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Article
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set htmlName
     *
     * @param string $htmlName
     *
     * @return Article
     */
    public function setHtmlName($htmlName)
    {
        $this->htmlName = $htmlName;

        return $this;
    }

    /**
     * Get htmlName
     *
     * @return string
     */
    public function getHtmlName()
    {
        return $this->htmlName;
    }

    /**
     * Set htmlNameEn
     *
     * @param string $htmlNameEn
     *
     * @return Article
     */
    public function setHtmlNameEn($htmlNameEn)
    {
        $this->htmlNameEn = $htmlNameEn;

        return $this;
    }

    /**
     * Get htmlNameEn
     *
     * @return string
     */
    public function getHtmlNameEn()
    {
        return $this->htmlNameEn;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Article
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set solde
     *
     * @param integer $solde
     *
     * @return Article
     */
    public function setSolde($solde)
    {
        $this->solde = $solde;

        return $this;
    }

    /**
     * Get solde
     *
     * @return integer
     */
    public function getSolde()
    {
        return $this->solde;
    }

    /**
     * Set isOffer
     *
     * @param boolean $isOffer
     *
     * @return Article
     */
    public function setIsOffer($isOffer)
    {
        $this->isOffer = $isOffer;

        return $this;
    }

    /**
     * Get isOffer
     *
     * @return boolean
     */
    public function getIsOffer()
    {
        return $this->isOffer;
    }

    /**
     * Set isSpecial
     *
     * @param boolean $isSpecial
     *
     * @return Article
     */
    public function setIsSpecial($isSpecial)
    {
        $this->isSpecial = $isSpecial;

        return $this;
    }

    /**
     * Get isSpecial
     *
     * @return boolean
     */
    public function getIsSpecial()
    {
        return $this->isSpecial;
    }

    /**
     * Set isNew
     *
     * @param boolean $isNew
     *
     * @return Article
     */
    public function setIsNew($isNew)
    {
        $this->isNew = $isNew;

        return $this;
    }

    /**
     * Get isNew
     *
     * @return boolean
     */
    public function getIsNew()
    {
        return $this->isNew;
    }

    /**
     * Set urlPicture
     *
     * @param string $urlPicture
     *
     * @return Article
     */
    public function setUrlPicture($urlPicture)
    {
        $this->urlPicture = $urlPicture;

        return $this;
    }

    /**
     * Get urlPicture
     *
     * @return string
     */
    public function getUrlPicture()
    {
        return $this->urlPicture;
    }

    /**
     * Set isPublished
     *
     * @param boolean $isPublished
     *
     * @return Article
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
     * Set rank
     *
     * @param integer $rank
     *
     * @return Article
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
     * Set createdBy
     *
     * @param integer $createdBy
     *
     * @return Article
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return integer
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set createdOn
     *
     * @param \DateTime $createdOn
     *
     * @return Article
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
     * @return Article
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
     * Set brand
     *
     * @param \AppBundle\Entity\Brand $brand
     *
     * @return Article
     */
    public function setBrand(\AppBundle\Entity\Brand $brand = null)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand
     *
     * @return \AppBundle\Entity\Brand
     */
    public function getBrand()
    {
        return $this->brand;
    }


    /**
     * Set category
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return Article
     */
    public function setCategory(\AppBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }


    /**
     * @return string
     */
    public function __toString() {
        return $this->name;
    }
}
