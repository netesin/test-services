<?php

namespace AppBundle\Entity;

use AppBundle\Services\Discount\OrderInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity()
 * @ORM\Table(name="Order")
 */
class Order implements OrderInterface
{
    /**
     * @var integer
     *
     * @ORM\Id()
     * @ORM\Column(type="bigint")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({
     *     "details",
     *     "list",
     * })
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Serializer\Groups({
     *     "details",
     *     "list",
     * })
     */
    protected $fio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     * @Serializer\Groups({
     *     "details",
     *     "list",
     * })
     */
    protected $birthday;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Serializer\Groups({
     *     "details",
     *     "list",
     * })
     */
    protected $phone;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=1, nullable=true)
     * @Serializer\Groups({
     *     "details",
     *     "list",
     * })
     */
    protected $gender;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Discount", inversedBy="orders")
     * @ORM\JoinColumn(name="discountId", nullable=true, referencedColumnName="id")
     * @Serializer\Groups({
     *     "details",
     *     "list",
     * })
     */
    protected $discount;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Service", inversedBy="orders")
     * @ORM\JoinTable(
     *     name="OrderServices",
     *     joinColumns={
     *          @ORM\JoinColumn(name="orderId", referencedColumnName="id", onDelete="CASCADE")
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(name="serviceId", referencedColumnName="id", onDelete="CASCADE")
     *     }
     * )
     * @Serializer\Groups({
     *     "details",
     *     "list",
     * })
     */
    protected $services;

    /**
     * Set gender
     *
     * @param string|null $gender
     * @return Order
     * @throws \Exception
     */
    public function setGender($gender)
    {
        $gender = strtolower($gender);

        if (!in_array($gender, ['f', 'm', null])) {
            throw new \Exception('$gender must be f, m or null');
        }

        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return strtolower($this->gender);
    }

    //

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->services = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set fio
     *
     * @param string $fio
     *
     * @return Order
     */
    public function setFio($fio)
    {
        $this->fio = $fio;

        return $this;
    }

    /**
     * Get fio
     *
     * @return string
     */
    public function getFio()
    {
        return $this->fio;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return Order
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set phone
     *
     * @param integer $phone
     *
     * @return Order
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return integer
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set discount
     *
     * @param \AppBundle\Entity\Discount $discount
     *
     * @return Order
     */
    public function setDiscount(\AppBundle\Entity\Discount $discount = null)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return \AppBundle\Entity\Discount
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Add service
     *
     * @param \AppBundle\Entity\Service $service
     *
     * @return Order
     */
    public function addService(\AppBundle\Entity\Service $service)
    {
        $this->services[] = $service;

        return $this;
    }

    /**
     * Remove service
     *
     * @param \AppBundle\Entity\Service $service
     */
    public function removeService(\AppBundle\Entity\Service $service)
    {
        $this->services->removeElement($service);
    }

    /**
     * Get services
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServices()
    {
        return $this->services;
    }
}
