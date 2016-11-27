<?php

namespace AppBundle\Services\Discount\Validators;

use AppBundle\Services\Discount\DiscountInterface;
use AppBundle\Services\Discount\OrderInterface;
use AppBundle\Services\Discount\ValidatorInterface;

class BirthdayValidator implements ValidatorInterface
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
        $birthday     = $order->getBirthday();
        $birthdayWeek = $discount->getBirthdayWeek();

        if (empty($birthday) || empty($birthdayWeek)) {
            return false;
        }

        $date = new \DateTime();
        $birthday->setDate($date->format('Y'), $birthday->format('m'), $birthday->format('d'));

        $isValid = false;

        switch ($birthdayWeek) {
            case 'before':

                // If birthday in interval current - 7 day ago;

                $date->sub(new \DateInterval('P7D'));
                $diff    = $date->diff($birthday);
                $isValid = ($diff->days <= 7 && !$diff->invert);
                break;

            case 'after':

                // If birthday in interval current + 7 day ago;

                $birthday->sub(new \DateInterval('P7D'));
                $diff    = $date->diff($birthday);
                $isValid = ($diff->days <= 7 && $diff->invert);
                break;

            case 'both':

                // If birthday in interval current + or - 7 day ago;

                $dateBefore     = clone $date;
                $birthdayBefore = clone $birthday;
                $dateAfter      = clone $date;
                $birthdayAfter  = clone $birthday;

                $dateBefore->sub(new \DateInterval('P7D'));
                $birthdayAfter->sub(new \DateInterval('P7D'));

                $diffBefore = $dateBefore->diff($birthdayBefore);
                $diffAfter  = $dateAfter->diff($birthdayAfter);
                $isValid    = (($diffBefore->days <= 7 && !$diffBefore->invert) || ($diffAfter->days <= 7 && $diffAfter->invert));
                break;
        }

        return $isValid;
    }

    /**
     * Check is discount can be validate by validator.
     *
     * @param DiscountInterface $discount
     * @return boolean
     */
    public function isAccept(DiscountInterface $discount)
    {
        return !empty($discount->getBirthdayWeek());
    }
}
