<?php
/* Copyright (c) 2019 Extended GPL, see docs/LICENSE */

namespace srag\CQRS\Event;

/**
 * Class DomainEvents
 *
 * List of domain events
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class DomainEvents
{

    /**
     * @var DomainEvent[]
     */
    private array $events;

    public function __construct()
    {
        $this->events = [];
    }

    public function addEvent(DomainEvent $event) : void
    {
        $this->events[] = $event;
    }

    public function addEvents(DomainEvents $events) : void
    {
        foreach ($events->getEvents() as $event) {
            $this->addEvent($event);
        }
    }

    /**
     * @return DomainEvent[]
     */
    public function getEvents() : array
    {
        return $this->events;
    }
}
