<?php

namespace AppBundle\Method\Order;

use AppBundle\Entity\Order;
use AppBundle\Method\AbstractMethod;
use Timiki\Bundle\RpcServerBundle\Mapping as RPC;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @RPC\Method("order.getDiscount")
 */
class GetDiscountMethod extends AbstractMethod
{
    /**
     * @Rpc\Param()
     * @Assert\NotBlank()
     */
    protected $fio;

    /**
     * @Rpc\Param()
     * @Assert\Date()
     */
    protected $birthday = null;

    /**
     * @Rpc\Param()
     * @Assert\Type("integer")
     */
    protected $phone = null;

    /**
     * @Rpc\Param()
     * @Assert\Choice({"f", "m", null})
     */
    protected $gender = null;

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
        $discountManager = $this->container->get('discount.manager');
        $order           = new Order();

        $order->setFio($this->fio);
        $order->setPhone($this->phone);
        $order->setGender($this->gender);

        if (!empty($this->birthday)) {
            $order->setBirthday(
                \DateTime::createFromFormat('Y-m-d', $this->birthday)
            );
        }

        foreach ($this->getServices($this->services) as $service) {
            $order->addService($service);
        }

        if ($discount = $discountManager->findFirstDiscountForOrder($order)) {
            return $discount->getDiscount();
        }

        return 0;
    }
}