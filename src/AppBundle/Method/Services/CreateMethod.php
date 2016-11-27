<?php

namespace AppBundle\Method\Services;

use AppBundle\Entity\Service;
use AppBundle\Method\AbstractMethod;
use Timiki\Bundle\RpcServerBundle\Mapping as RPC;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @RPC\Method("services.create")
 */
class CreateMethod extends AbstractMethod
{
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
        $em      = $this->container->get('doctrine.orm.entity_manager');
        $service = new Service();

        $service->setName($this->name);

        $em->persist($service);
        $em->flush($service);

        return $this->serialize($service);
    }
}