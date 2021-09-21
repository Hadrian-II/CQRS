<?php
/* Copyright (c) 2019 Extended GPL, see docs/LICENSE */

namespace Fluxlabs\CQRS\Aggregate;

use Fluxlabs\CQRS\Event\DomainEvent;
use Fluxlabs\CQRS\Event\DomainEvents;
use Fluxlabs\CQRS\Event\Standard\AggregateCreatedEvent;
use Fluxlabs\CQRS\Exception\CQRSException;
use Fluxlabs\CQRS\Event\Standard\AggregateDeletedEvent;
use ILIAS\Data\UUID\Uuid;

/**
 * Class AbstractAggregateRoot
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
abstract class AbstractAggregateRoot
{
    const APPLY_PREFIX = 'apply';

    protected Uuid $aggregate_id;

    private DomainEvents $recordedEvents;

    private bool $is_deleted = false;

    protected function __construct()
    {
        $this->recordedEvents = new DomainEvents();
    }

    protected function ExecuteEvent(DomainEvent $event) : void
    {
        if ($this->is_deleted) {
            throw new CQRSException("Action on deleted Aggregate not allowed");
        }

        // apply results of event to class, most events should result in some changes
        $this->applyEvent($event);

        // always record that the event has happened
        $this->recordEvent($event);
    }

    protected function recordEvent(DomainEvent $event) : void
    {
        $this->recordedEvents->addEvent($event);
    }

    protected function applyEvent(DomainEvent $event) : void
    {
        $action_handler = $this->getHandlerName($event);

        if (method_exists($this, $action_handler)) {
            $this->$action_handler($event);
        }
    }

    protected function applyAggregateCreatedEvent(DomainEvent $event) : void
    {
        $this->aggregate_id = $event->getAggregateId();
    }

    protected function applyAggregateDeletedEvent(DomainEvent $event) : void
    {
        $this->is_deleted = true;
    }

    private function getHandlerName(DomainEvent $event) : string
    {
        return self::APPLY_PREFIX . join('', array_slice(explode('\\', get_class($event)), -1));
    }

    public function getRecordedEvents() : DomainEvents
    {
        return $this->recordedEvents;
    }

    public function clearRecordedEvents() : void
    {
        $this->recordedEvents = new DomainEvents();
    }

    public function getAggregateId() : Uuid
    {
        return $this->aggregate_id;
    }

    public static function reconstitute(DomainEvents $event_history) : AbstractAggregateRoot
    {
        $aggregate_root = new static();
        foreach ($event_history->getEvents() as $event) {
            $aggregate_root->applyEvent($event);
        }

        return $aggregate_root;
    }
}
