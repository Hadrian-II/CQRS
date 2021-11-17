<?php
/* Copyright (c) 2019 Extended GPL, see docs/LICENSE */

namespace Fluxlabs\CQRS\Event;

use DateTimeImmutable;
use ILIAS\Data\UUID\Uuid;

/**
 * Interface DomainEvent
 *
 * Something that happened in the past, that is of importance to the business.
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
interface DomainEvent
{

    public function getEventId() : string;

    public function getAggregateId() : Uuid;

    public function getEventName() : string;

    public function getOccurredOn() : DateTimeImmutable;

    public function getInitiatingUserId() : int;

    public function getEventBody() : string;
}
