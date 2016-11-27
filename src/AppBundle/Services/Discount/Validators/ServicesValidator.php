<?php

namespace AppBundle\Services\Discount\Validators;

use AppBundle\Services\Discount\DiscountInterface;
use AppBundle\Services\Discount\OrderInterface;
use AppBundle\Services\Discount\ValidatorInterface;

class ServicesValidator implements ValidatorInterface
{
    /**
     * Check is discount can be apply for order.
     *
     * @param DiscountInterface $discount
     * @param OrderInterface    $order
     * @return boolean
     */
    public function isValid(DiscountInterface $discount, OrderInterface $order)
    {
        if (count($discount->getServices()) === 0) {
            return false;
        }

        $orderServicesIds = [];
        $inServices       = [];

        foreach ($order->getServices() as $service) {
            $orderServicesIds[] = $service->getId();
        }

        foreach ($discount->getServices() as $service) {
            $inServices[] = in_array($service->getId(), $orderServicesIds);
        }

        return !in_array(false, $inServices);
    }

    /**
     * Check is discount can be validate by validator.
     *
     * @param DiscountInterface $discount
     * @return boolean
     */
    public function isAccept(DiscountInterface $discount)
    {
        return count($discount->getServices()) > 0;
    }
}
