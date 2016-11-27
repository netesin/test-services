<?php

namespace AppBundle\Services\Discount\Validators;

use AppBundle\Services\Discount\DiscountInterface;
use AppBundle\Services\Discount\OrderInterface;
use AppBundle\Services\Discount\ValidatorInterface;

class IsPhoneMatchValidator implements ValidatorInterface
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
        $phone       = $order->getPhone();
        $phoneEnding = $discount->getPhoneEnding();

        return (!empty($phoneEnding) && !empty($phone) && strlen($phone) >= 4 && ((integer)substr($phone, -4) === (integer)$phoneEnding));
    }

    /**
     * Check is discount can be validate by validator.
     *
     * @param DiscountInterface $discount
     * @return boolean
     */
    public function isAccept(DiscountInterface $discount)
    {
        return !empty($discount->getPhoneEnding());
    }
}
