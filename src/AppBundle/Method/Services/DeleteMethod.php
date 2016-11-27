<?php

namespace AppBundle\Method\Services;

use AppBundle\Method\AbstractMethod;
use Timiki\Bundle\RpcServerBundle\Mapping as RPC;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @RPC\Method("services.delete")
 */
class DeleteMethod extends AbstractMethod
{
    /**
     * @Rpc\Param()
     * @Assert\NotBlank()
     * @Assert\Type(type="integer")
     * @Assert\GreaterThan(0)
     */
    protected $id;

    /**
     * @Rpc\Execute()
     */
    public function execute()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $qb = $em->createQueryBuilder();

        $qb->delete('AppBundle:Service', 'service');
        $qb->where('service.id = :id');
        $qb->setParameter('id', $this->id);

        return (boolean)$qb->getQuery()->getOneOrNullResult();
    }
}