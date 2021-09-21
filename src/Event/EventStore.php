<?php
/* Copyright (c) 2019 Extended GPL, see docs/LICENSE */

namespace Fluxlabs\CQRS\Event;

use Fluxlabs\CQRS\Exception\CQRSException;
use ilDateTime;
use ILIAS\Data\UUID\Factory;
use ILIAS\Data\UUID\Uuid;

/**
 * Abstract Class EventStore
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
abstract class EventStore implements IEventStore
{
    public function commit(DomainEvents $events) : void
    {
        $uuid_factory = new Factory();

        /** @var AbstractDomainEvent $event */
        foreach ($events->getEvents() as $event) {
            $ar_class = $this->getEventArClass();
            $stored_event = new $ar_class();

            $stored_event->setEventData(
                $uuid_factory->uuid4AsString(),
                $event::getEventVersion(),
                $event->getAggregateId(),
                $event->getEventName(),
                $event->getOccurredOn(),
                $event->getInitiatingUserId(),
                $event->getEventBody(),
                get_class($event)
            );

            $stored_event->create();
        }
    }

    public function aggregateExists(Uuid $id) : bool
    {
        global $DIC;

        $sql = sprintf(
            'SELECT Count(*) as count FROM %s where aggregate_id = %s',
            $this->getStorageName(),
            $DIC->database()->quote($id->toString(), 'string')
            );

        $res = $DIC->database()->query($sql);

        return $DIC->database()->fetchAssoc($res)['count'] > 0;
    }

    public function getAggregateHistoryFor(Uuid $id) : DomainEvents
    {
        global $DIC;

        $sql = sprintf(
            'SELECT * FROM %s where aggregate_id = %s',
            $this->getStorageName(),
            $DIC->database()->quote($id->toString(), 'string')
        );

        $res = $DIC->database()->query($sql);

        if ($res->rowCount() === 0) {
            throw new CQRSException('Aggregate does not exist');
        }

        $event_stream = new DomainEvents();

        while ($row = $DIC->database()->fetchAssoc($res)) {
            /**@var AbstractDomainEvent $event */
            $event_name = $row['event_class'];
            $event = $event_name::restore(
                $row['event_id'],
                intval($row['event_version']),
                $id,
                intval($row['initiating_user_id']),
                new ilDateTime($row['occurred_on']),
                $row['event_body']
            );
            $event_stream->addEvent($event);
        }

        return $event_stream;
    }

    public function getEventStream(?string $from_id = null) : DomainEvents
    {
        global $DIC;

        $sql = sprintf('SELECT * FROM %s', $this->getStorageName());

        if (!is_null($from_id)) {
            $sql .= sprintf(
                ' WHERE id > (SELECT id FROM %s WHERE event_id = "%s")',
                $this->getStorageName(),
                $from_id
            );
        }

        $res = $DIC->database()->query($sql);

        $event_stream = new DomainEvents();
        while ($row = $DIC->database()->fetchAssoc($res)) {
            /**@var AbstractDomainEvent $event */
            $event_name = $row['event_class'];
            $event = $event_name::restore(
                $row['event_id'],
                intval($row['event_version']),
                $row['aggregate_id'],
                intval($row['initiating_user_id']),
                new ilDateTime($row['occurred_on']),
                $row['event_body']
            );
            $event_stream->addEvent($event);
        }

        return $event_stream;
    }

    protected function getStorageName() : string
    {
        return call_user_func($this->getEventArClass() . '::returnDbTableName');
    }

    /**
     * Gets the Active Record class that is used for the event store
     */
    abstract protected function getEventArClass() : string;
}
