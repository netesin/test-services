<?php

namespace AppBundle\Listener;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class NotFoundListener implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if ($event->getResponse()->getStatusCode() === 404) {
            $event->getResponse()->setStatusCode(200);
        }
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // Return index.html on not found route for angular.

        $response   = new Response();
        $twig       = $this->container->get('twig');

        $response->setContent(
            $twig->render('@App/index.html.twig')
        );

        $response->setStatusCode(200);

        $event->setResponse($response);
    }
}