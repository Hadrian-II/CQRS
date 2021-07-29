<?php
/* Copyright (c) 2019 Extended GPL, see docs/LICENSE */

namespace srag\CQRS\Event;

use ILIAS\Data\UUID\Uuid;

/**
 * Interface IEventStore
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
interface IEventStore
{
    public function commit(DomainEvents $events) : void;

    public function aggregateExists(Uuid $id) : bool;

    public function getAggregateHistoryFor(Uuid $id) : DomainEvents;

    public function getEventStream(?string $from_id = null) : DomainEvents;
}