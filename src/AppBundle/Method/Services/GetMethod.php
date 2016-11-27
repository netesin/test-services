<?php

namespace AppBundle\Method\Services;

use AppBundle\Method\AbstractMethod;
use Timiki\Bundle\RpcServerBundle\Mapping as RPC;

/**
 * @RPC\Method("services.get")
 */
class GetMethod extends AbstractMethod
{
    /**
     * @Rpc\Execute()
     */
    public function execute()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');

        return $this->serialize(
            $em->getRepository('AppBundle:Service')->findAll()
        );
    }
}