<?php
namespace Application\MainBundle\Listener;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class ApplicationMainListener
{

    protected $container;

    public function __construct(\Symfony\Component\HttpKernel\Kernel $kernel)
    {
        $this->container = $kernel->getContainer();
    }

    public function handleCoreController(FilterControllerEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST == $event->getRequestType()) {
            $request = $this->container->get("request");
            $session = $request->getSession();
            $request->setLocale($session->get("locale", $request->getLocale()));
        }
    }
}