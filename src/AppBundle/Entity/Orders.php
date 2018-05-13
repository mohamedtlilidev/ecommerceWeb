<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Orders
 *
 * @ORM\Table(name="orders")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrdersRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Orders
{
    /**
     * Article constructor.
     */
    public function __construct() {
        $this->setCreatedTime(new \DateTime());
        $this->setUpdatedTime(new \DateTime());
        $this->setViewed(false);
    }
    /**
     * @ORM\PreUpdate
     */
    public function PreUpdate() {
        $this->setUpdatedTime(new \DateTime());
    }
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;


    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", nullable=true)
     */
    protected $title;
    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string")
     */
    protected $reference;
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", nullable=true)
     */
    protected $description;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", precision=10, scale=2)
     */
    protected $price = '0.00';

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float", precision=10, scale=2)
     */
    protected $amount = '0.00';

    /**
     * @ORM\ManyToOne(targetEntity="OrderStatus",inversedBy="orders")
     * @ORM\JoinColumn(name="order_status_id", referencedColumnName="id")
     */
    protected $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdTime", type="datetime",options={"default"="CURRENT_TIMESTAMP"}, nullable=false)
     */
    protected $createdTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedTime", type="datetime",options={"default"="CURRENT_TIMESTAMP"}, nullable=false)
     */
    protected $updatedTime;

    /**
     * @var string
     *
     * @ORM\Column(name="data", type="array", nullable=true)
     */
    protected $data = array();

    /**
     * @ORM\OneToMany(targetEntity="OrderItem", mappedBy="order")
     */
    protected $items = [];

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="viewed_by", referencedColumnName="id")
     */
    protected $viewedBy;

    /**
     * @var boolean
     *
     * @ORM\Column(name="viewed", type="boolean")
     */
    protected $viewed;
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedTime()
    {
        return $this->createdTime;
    }

    /**
     * @param \DateTime $createdTime
     */
    public function setCreatedTime($createdTime)
    {
        $this->createdTime = $createdTime;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedTime()
    {
        return $this->updatedTime;
    }

    /**
     * @param \DateTime $updatedTime
     */
    public function setUpdatedTime($updatedTime)
    {
        $this->updatedTime = $updatedTime;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param mixed $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }


    /**
     * @return mixed
     */
    public function getViewedBy()
    {
        return $this->viewedBy;
    }

    /**
     * @param mixed $viewedBy
     */
    public function setViewedBy($viewedBy)
    {
        $this->viewedBy = $viewedBy;
    }

    /**
     * @return bool
     */
    public function isViewed()
    {
        return $this->viewed;
    }

    /**
     * @param bool $viewed
     */
    public function setViewed($viewed)
    {
        $this->viewed = $viewed;
    }







    /**
     * @return string
     */
    public function __toString() {
        return $this->reference;
    }


}

