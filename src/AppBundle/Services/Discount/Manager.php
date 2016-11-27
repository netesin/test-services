<?php

namespace AppBundle\Services\Discount;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use AppBundle\Services\Discount\Validators;

class Manager
{
    /**
     * Array of discount validation.
     *
     * @var ValidatorInterface[]
     */
    private $validators = [];

    /**
     * Entity manager.
     *
     * @var EntityManager
     */
    private $em;

    /**
     * Manager constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;

        // Set default validators;

        $this->validators = [
            new Validators\BirthdayValidator(),
            new Validators\GenderValidator(),
            new Validators\IsPhoneMatchValidator(),
            new Validators\IsPhoneRequiredValidator(),
            new Validators\ServicesValidator(),
        ];
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     * Check is discount can be apply for order.
     *
     * @param DiscountInterface $discount
     * @param OrderInterface    $order
     * @return boolean
     */
    public function isValid(DiscountInterface $discount, OrderInterface $order)
    {
        $validResult = [];

        foreach ($this->validators as $validator) {
            if ($validator->isAccept($discount)) {
                $validResult[] = $validator->isValid($discount, $order);
            }
        }

        return !in_array(false, $validResult);
    }

    /**
     * Find first discount for order.
     *
     * @param OrderInterface $order
     * @return DiscountInterface|null
     */
    public function findFirstDiscountForOrder(OrderInterface $order)
    {
        foreach ($this->getActiveDiscounts() as $discount) {

            if ($this->isValid($discount, $order)) {
                return $discount;
            }
        }

        return null;
    }

    /**
     * Get array active discount.
     *
     * @return DiscountInterface[]
     */
    public function getActiveDiscounts()
    {
        $qb = $this->getEntityManager()->getRepository('AppBundle:Discount')->createQueryBuilder('discount');

        $qb->andWhere('discount.activatedAt <= :date');
        $qb->andWhere(
            $qb->expr()->orX(
                $qb->expr()->andX('discount.activatedTo >= :date'),
                $qb->expr()->andX('discount.activatedTo IS NULL')
            )
        );
        $qb->leftJoin('discount.services', 'services');
        $qb->addSelect('services');
        $qb->addOrderBy('discount.discount', 'DESC');
        $qb->addOrderBy('discount.id');
        $qb->setParameter('date', (new \DateTime())->format('Y-m-d'));

        return $qb->getQuery()->execute();
    }

}
