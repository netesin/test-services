<?php

namespace AppBundle\Services\Discount\Validators;

use AppBundle\Services\Discount\DiscountInterface;
use AppBundle\Services\Discount\OrderInterface;
use AppBundle\Services\Discount\ValidatorInterface;

class IsPhoneRequiredValidator implements ValidatorInterface
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
        return ($discount->isPhoneRequired() && !empty($order->getPhone()));
    }

    /**
     * Check is discount can be validate by validator.
     *
     * @param DiscountInterface $discount
     * @return boolean
     */
    public function isAccept(DiscountInterface $discount)
    {
        return !empty($discount->isPhoneRequired());
    }
}
