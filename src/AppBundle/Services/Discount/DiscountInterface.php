<?php

namespace AppBundle\Services\Discount;

interface DiscountInterface
{
    /**
     * Is need phone for discount.
     *
     * @return boolean
     */
    public function isPhoneRequired();

    /**
     * Get phone ending for discount.
     *
     * @return integer|null
     */
    public function getPhoneEnding();

    /**
     * Get gender for discount.
     *
     * @return string|null
     */
    public function getGender();

    /**
     * Get birthday week for discount.
     *
     * @return string|null
     */
    public function getBirthdayWeek();

    /**
     * Get gender for discount.
     *
     * @return integer
     */
    public function getDiscount();

    /**
     * Get services.
     *
     * @return ServiceInterface[]
     */
    public function getServices();

    /**
     * Get datetime activatedAt.
     *
     * @return \DateTime
     */
    public function getActivatedAt();

    /**
     * Get datetime activatedTo.
     *
     * @return \DateTime|null
     */
    public function getActivatedTo();
}
