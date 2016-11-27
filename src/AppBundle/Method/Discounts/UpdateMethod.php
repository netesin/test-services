<?php

namespace AppBundle\Method\Discounts;

use AppBundle\Entity\Discount;
use AppBundle\Method\AbstractMethod;
use Timiki\Bundle\RpcServerBundle\Mapping as RPC;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @RPC\Method("discounts.update")
 */
class UpdateMethod extends AbstractMethod
{
    /**
     * @Rpc\Param()
     * @Assert\NotBlank()
     * @Assert\Type(type="integer")
     * @Assert\GreaterThan(0)
     */
    protected $id;

    /**
     * @Rpc\Param()
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @Rpc\Param()
     * @Assert\Choice({"before", "after", "both", null})
     */
    protected $birthdayWeek = null;

    /**
     * @Rpc\Param()
     * @Assert\Type("boolean")
     */
    protected $isPhoneRequired = false;

    /**
     * @Rpc\Param()
     * @Assert\Type("integer")
     * @Assert\Length(max="4")
     */
    protected $phoneEnding = null;

    /**
     * @Rpc\Param()
     * @Assert\Choice({"f", "m", null})
     */
    protected $gender = null;

    /**
     * @Rpc\Param()
     * @Assert\Type("integer")
     * @Assert\GreaterThanOrEqual(0)
     * @Assert\LessThanOrEqual(100)
     * @Assert\NotBlank()
     */
    protected $discount;

    /**
     * @Rpc\Param()
     * @Assert\Date()
     */
    protected $activatedAt = null;

    /**
     * @Rpc\Param()
     * @Assert\Date()
     */
    protected $activatedTo = null;

    /**
     * @Rpc\Param()
     * @Assert\Collection()
     */
    protected $services = [];

    /**
     * @Rpc\Execute()
     */
    public function execute()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');

        /* @var Discount $discount */
        if ($discount = $em->getRepository('AppBundle:Discount')->find($this->id)) {

            $discount->setName($this->name);
            $discount->setDiscount($this->discount);
            $discount->setBirthdayWeek($this->birthdayWeek);
            $discount->setGender($this->gender);
            $discount->setIsPhoneRequired((boolean)$this->isPhoneRequired);
            $discount->setPhoneEnding($this->phoneEnding);

            if (!empty($this->activatedAt)) {
                $discount->setActivatedAt(
                    \DateTime::createFromFormat('Y-m-d', $this->activatedAt)
                );
            }

            if (!empty($this->activatedTo)) {
                $discount->setActivatedTo(
                    \DateTime::createFromFormat('Y-m-d', $this->activatedTo)
                );
            }

            foreach ($discount->getServices() as $service) {
                $discount->removeService($service);
            }

            foreach ($this->getServices($this->services) as $service) {
                $discount->addService($service);
            }

            $em->flush();
        }

        return isset($discount);
    }
}