<?php

namespace Samson\Bundle\UnexpectedResponseBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Samson\Bundle\UnexpectedResponseBundle\Exception\UnexpectedResponseException;

/**
 * @author Bart van den Burg <bart@samson-it.nl>
 */
class UnexpectedResponseListener
{

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof UnexpectedResponseException) {
            $event->setResponse($exception->getResponse());
        }

        // allow unexpected response in twig
        $class = '\Twig_Error_Runtime';
        if ($exception instanceof $class) {
            $exception = $exception->getPrevious();
            if ($exception instanceof UnexpectedResponseException) {
                $event->setResponse($exception->getResponse());
            }
        }
    }
}
