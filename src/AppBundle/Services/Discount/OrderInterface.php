<?php

namespace AppBundle\Services\Discount;

interface OrderInterface
{
    /**
     * Get birthday.
     *
     * @return \DateTime|null
     */
    public function getBirthday();

    /**
     * Get phone.
     *
     * @return integer|null
     */
    public function getPhone();

    /**
     * Get gender.
     *
     * @return string|null
     */
    public function getGender();

    /**
     * Get services.
     *
     * @return ServiceInterface[]
     */
    public function getServices();
}
