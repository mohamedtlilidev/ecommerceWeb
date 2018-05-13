<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderStatus
 *
 * @ORM\Table(name="order_status")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderStatusRepository")
 */
class OrderStatus
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="defaults", type="boolean")
     */
    private $defaults=false;
    /**
     * @var string
     *
     * @ORM\Column(name="default_cancel", type="boolean")
     */
    private $default_cancel=false;


    /**
     * One Status has Many Orders.
     * @ORM\OneToMany(targetEntity="Orders", mappedBy="status")
     */
    private $orders;
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
     * Set name
     *
     * @param string $name
     *
     * @return OrderStatus
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
     * @return string
     */
    public function getDefaults()
    {
        return $this->defaults;
    }

    /**
     * @param string $defaults
     */
    public function setDefaults($defaults)
    {
        $this->defaults = $defaults;
    }



    /**
     * @return mixed
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * @param mixed $orders
     */
    public function setOrders($orders)
    {
        $this->orders = $orders;
    }

    /**
     * @return string
     */
    public function __toString() {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDefaultCancel()
    {
        return $this->default_cancel;
    }

    /**
     * @param string $default_cancel
     */
    public function setDefaultCancel($default_cancel)
    {
        $this->default_cancel = $default_cancel;
    }


}

