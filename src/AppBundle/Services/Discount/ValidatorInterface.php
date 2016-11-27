<?php

namespace AppBundle\Services\Discount;


interface ValidatorInterface
{
    /**
     * Check is discount can be apply for order.
     *
     * @param DiscountInterface $discount
     * @param OrderInterface    $order
     * @return boolean
     */
    public function isValid(DiscountInterface $discount, OrderInterface $order);

    /**
     * Check is discount can be validate by validator.
     *
     * @param DiscountInterface $discount
     * @return boolean
     */
    public function isAccept(DiscountInterface $discount);
}
