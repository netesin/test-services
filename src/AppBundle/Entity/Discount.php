<?php

namespace AppBundle\Entity;

use AppBundle\Services\Discount\DiscountInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity()
 * @ORM\Table(name="Discount")
 */
class Discount implements DiscountInterface
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
     * @var integer
     *
     * @ORM\Column(type="string")
     * @Serializer\Groups({
     *     "details",
     *     "list",
     * })
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="birthdayWeek", type="string", length=6, nullable=true)
     * @Serializer\Groups({
     *     "details",
     *     "list",
     * })
     * @Serializer\SerializedName("birthdayWeek")
     */
    protected $birthdayWeek;

    /**
     * @var string
     *
     * @ORM\Column(name="isPhoneRequired", type="boolean", nullable=true)
     * @Serializer\Groups({
     *     "details",
     *     "list",
     * })
     * @Serializer\SerializedName("isPhoneRequired")
     */
    protected $isPhoneRequired;

    /**
     * @var string
     *
     * @ORM\Column(name="phoneEnding", type="integer", length=4, nullable=true)
     * @Serializer\Groups({
     *     "details",
     *     "list",
     * })
     * @Serializer\SerializedName("phoneEnding")
     */
    protected $phoneEnding;

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
     * @var integer
     *
     * @ORM\Column(type="integer", length=3)
     * @Serializer\Groups({
     *     "details",
     *     "list",
     * })
     */
    protected $discount = 0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="activatedAt", type="date")
     * @Serializer\Groups({
     *     "details",
     *     "list",
     * })
     * @Serializer\SerializedName("activatedAt")
     * @Serializer\Type("DateTime<'Y-m-d'>")
     */
    protected $activatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="activatedTo", type="date", nullable=true)
     * @Serializer\Groups({
     *     "details",
     *     "list",
     * })
     * @Serializer\SerializedName("activatedTo")
     * @Serializer\Type("DateTime<'Y-m-d'>")
     */
    protected $activatedTo;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Service", inversedBy="discounts")
     * @ORM\JoinTable(
     *     name="DiscountServices",
     *     joinColumns={
     *          @ORM\JoinColumn(name="discountId", referencedColumnName="id", onDelete="CASCADE")
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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Order", mappedBy="discount")
     */
    protected $orders;

    /**
     * Set gender
     *
     * @param string|null $gender
     * @return Discount
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

    /**
     * Set birthdayWeek
     *
     * @param string|null $birthdayWeek
     * @return Discount
     * @throws \Exception
     */
    public function setBirthdayWeek($birthdayWeek)
    {
        $birthdayWeek = strtolower($birthdayWeek);

        if (!in_array($birthdayWeek, ['before', 'after', 'both', null])) {
            throw new \Exception('$birthdayWeek must be before, after, both or null');
        }

        $this->birthdayWeek = $birthdayWeek;

        return $this;
    }

    /**
     * Get birthdayWeek
     *
     * @return string
     */
    public function getBirthdayWeek()
    {
        return strtolower($this->birthdayWeek);
    }

    /**
     * Set discount
     *
     * @param integer $discount
     * @return Discount
     * @throws \Exception
     */
    public function setDiscount($discount)
    {
        if (!is_numeric($discount)) {
            throw new \Exception('$discount must numeric');
        }

        if ($discount < 0 || $discount > 100) {
            throw new \Exception('$discount must be >= 0 and <= 100');
        }

        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return integer
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Is need phone for discount.
     *
     * @return boolean
     */
    public function isPhoneRequired()
    {
        return $this->isPhoneRequired;
    }

    //

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->services = new \Doctrine\Common\Collections\ArrayCollection();
        $this->orders   = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Discount
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
     * Set isPhoneRequired
     *
     * @param boolean $isPhoneRequired
     *
     * @return Discount
     */
    public function setIsPhoneRequired($isPhoneRequired)
    {
        $this->isPhoneRequired = $isPhoneRequired;

        return $this;
    }

    /**
     * Get isPhoneRequired
     *
     * @return boolean
     */
    public function getIsPhoneRequired()
    {
        return $this->isPhoneRequired;
    }

    /**
     * Set phoneEnding
     *
     * @param integer $phoneEnding
     *
     * @return Discount
     */
    public function setPhoneEnding($phoneEnding)
    {
        $this->phoneEnding = $phoneEnding;

        return $this;
    }

    /**
     * Get phoneEnding
     *
     * @return integer
     */
    public function getPhoneEnding()
    {
        return $this->phoneEnding;
    }

    /**
     * Set activatedAt
     *
     * @param \DateTime $activatedAt
     *
     * @return Discount
     */
    public function setActivatedAt($activatedAt)
    {
        $this->activatedAt = $activatedAt;

        return $this;
    }

    /**
     * Get activatedAt
     *
     * @return \DateTime
     */
    public function getActivatedAt()
    {
        return $this->activatedAt;
    }

    /**
     * Set activatedTo
     *
     * @param \DateTime $activatedTo
     *
     * @return Discount
     */
    public function setActivatedTo($activatedTo)
    {
        $this->activatedTo = $activatedTo;

        return $this;
    }

    /**
     * Get activatedTo
     *
     * @return \DateTime
     */
    public function getActivatedTo()
    {
        return $this->activatedTo;
    }

    /**
     * Add service
     *
     * @param \AppBundle\Entity\Service $service
     *
     * @return Discount
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

    /**
     * Add order
     *
     * @param \AppBundle\Entity\Order $order
     *
     * @return Discount
     */
    public function addOrder(\AppBundle\Entity\Order $order)
    {
        $this->orders[] = $order;

        return $this;
    }

    /**
     * Remove order
     *
     * @param \AppBundle\Entity\Order $order
     */
    public function removeOrder(\AppBundle\Entity\Order $order)
    {
        $this->orders->removeElement($order);
    }

    /**
     * Get orders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrders()
    {
        return $this->orders;
    }
}
