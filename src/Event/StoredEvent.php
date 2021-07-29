<?php
/* Copyright (c) 2019 Extended GPL, see docs/LICENSE */

namespace srag\CQRS\Event;

/**
 * Interface StoredEvent
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
interface StoredEvent
{
    public static function returnDbTableName() : string;

    public function getEventId() : int;

    public function getAggregateId() : string;

    public function getEventName() : string;

    public function getOccuredOn() : int;

    public function getInitiatingUserId() : int;

    public function getEventBody() : string;

    public function create() : void;
}
