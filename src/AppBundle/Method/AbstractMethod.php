<?php

namespace AppBundle\Method;

use AppBundle\Entity\Service;
use JMS\Serializer\SerializationContext;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class AbstractMethod implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * Get service list.
     *
     * @param mixed $services
     * @return Service[]
     */
    public function getServices($services)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $qb = $em->getRepository('AppBundle:Service')->createQueryBuilder('service');

        $servicesIds = [];

        foreach ((array)$services as $service) {
            if (is_array($service) && isset($service['id'])) {
                $servicesIds[] = $service['id'];
            } elseif (is_numeric($service)) {
                $servicesIds[] = $service;
            }
        }

        if (count($servicesIds) === 0) {
            return [];
        }

        $qb->where(
            $qb->expr()->in('service.id', ':ids')
        );
        $qb->setParameter('ids', $servicesIds);

        return $qb->getQuery()->execute();
    }

    /**
     * Serialize mixed value to array
     *
     * @param mixed             $data
     * @param null|array|string $groups
     * @param bool              $serializeNull
     *
     * @return mixed
     */
    protected function serialize($data, $groups = null, $serializeNull = true)
    {
        if (!is_array($data) && !is_object($data)) {
            return $data;
        }

        if ($groups === 'all') {
            $groups = ['list', 'details', 'all'];
        }

        if ($groups === null) {
            if (is_object($data)) {
                $groups = ['details'];
            } else {
                $groups = ['list'];
            }
        }

        return $this->container
            ->get('jms_serializer')
            ->toArray(
                $data,
                SerializationContext::create()
                    ->enableMaxDepthChecks()
                    ->setSerializeNull($serializeNull)
                    ->setGroups((array)$groups)
            );
    }
}