<?php
/* Copyright (c) 2019 Extended GPL, see docs/LICENSE */

namespace Fluxlabs\CQRS\Event;

use Exception;

/**
 * Class AggregateHistory
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
final class AggregateHistory extends DomainEvents
{
    private string $aggregate_Id;

    public function __construct(string $aggregate_Id, array $events)
    {
        /** @var $event DomainEvent */
        foreach ($events as $event) {
            if (!$event->getAggregateId()->equals($aggregate_Id)) {
                throw new Exception();
            }
        }
        parent::__construct();

        foreach ($events as $event) {
            $this->addEvent($event);
        }

        $this->aggregate_Id = $aggregate_Id;
    }

    public function getAggregateId() : string
    {
        return $this->aggregate_Id;
    }

    public function append(DomainEvent $domainEvent)
    {
        $this->addEvent($domainEvent);
    }
}
