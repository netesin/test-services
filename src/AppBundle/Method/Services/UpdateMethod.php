<?php

namespace AppBundle\Method\Services;

use AppBundle\Entity\Service;
use AppBundle\Method\AbstractMethod;
use Timiki\Bundle\RpcServerBundle\Mapping as RPC;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @RPC\Method("services.update")
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
     * @Rpc\Execute()
     */
    public function execute()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');

        $qb = $em->createQueryBuilder();

        $qb->update('AppBundle:Service', 'service');
        $qb->where('service.id = :id');
        $qb->set('service.name', ':name');
        $qb->setParameter('id', $this->id);
        $qb->setParameter('name', $this->name);

        return (boolean)$qb->getQuery()->getOneOrNullResult();
    }
}