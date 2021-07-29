<?php

namespace srag\CQRS\Projection;

use srag\CQRS\Event\DomainEvent;

/**
 * Class ProjectionEventHandler
 *
 * @package srag\CQRS\Projection
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class ProjectionEventHandler
{
    public function handle(DomainEvent $event, Projector $projector) : void
    {
        $method = $this->handlerFunctionName($event->getEventName());
        if (method_exists($projector, $method)) {
            $projector->$method($event);
        }
    }

    private function handlerFunctionName(string $type) : string
    {
        return "when" . $type;
    }
}
