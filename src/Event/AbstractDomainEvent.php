<?php
/* Copyright (c) 2019 Extended GPL, see docs/LICENSE */

namespace srag\CQRS\Event;

use ILIAS\Data\UUID\Uuid;
use ilDateTime;
use srag\CQRS\Exception\CQRSException;

/**
 * Class AbstractDomainEvent
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
abstract class AbstractDomainEvent implements DomainEvent
{
    protected Uuid $event_id;

    protected string $aggregate_id;

    protected ilDateTime $occurred_on;

    protected int $initiating_user_id;

    protected function __construct(Uuid $aggregate_id, ilDateTime $occurred_on, int $initiating_user_id)
    {
        $this->aggregate_id = $aggregate_id;
        $this->occurred_on = $occurred_on;
        $this->initiating_user_id = $initiating_user_id;
    }

    public function getEventId() : string
    {
        return $this->event_id;
    }

    /**
     * The Aggregate this event belongs to.
     */
    public function getAggregateId() : Uuid
    {
        return $this->aggregate_id;
    }

    /**
     * Add a Constant EVENT_NAME to your class: Name it: [aggregate].[event]
     * e.g. 'question.created'
     */
    public function getEventName() : string
    {
        return get_called_class();
    }

    public function getOccurredOn() : ilDateTime
    {
        return $this->occurred_on;
    }

    public function getInitiatingUserId() : int
    {
        return $this->initiating_user_id;
    }

    abstract public function getEventBody() : string;

    abstract public static function getEventVersion() : int;

    public static function restore(
        string $event_id,
        int $event_version,
        Uuid $aggregate_id,
        int $initiating_user_id,
        ilDateTime $occurred_on,
        string $event_body
    ) : AbstractDomainEvent {
        $restored = new static($aggregate_id, $occurred_on, $initiating_user_id);
        $restored->event_id = $event_id;

        if (static::getEventVersion() < $event_version) {
            throw new CQRSException('Event store contains future versions of Events, ILIAS update necessary');
        }

        $restored->processEventBody($event_body, $event_version);

        return $restored;
    }

    private function processEventBody(string $event_body, int $event_version) : void
    {
        if (static::getEventVersion() === $event_version) {
            $this->restoreEventBody($event_body);
        } else {
            $this->restoreOldEventBody($event_body, $event_version);
        }
    }

    abstract protected function restoreEventBody(string $event_body) : void;

    protected function restoreOldEventBody(string $old_event_body, int $old_version) : DomainEvent
    {
        throw new CQRSException("Used ILIAS not compatible with available EventStore");
    }
}
