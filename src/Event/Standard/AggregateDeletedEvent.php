<?php
/* Copyright (c) 2019 Extended GPL, see docs/LICENSE */

namespace Fluxlabs\CQRS\Event\Standard;

use Fluxlabs\CQRS\Event\AbstractDomainEvent;
use DateTimeImmutable;
use ILIAS\Data\UUID\Uuid;

/**
 * Class AggregateDeletedEvent
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class AggregateDeletedEvent extends AbstractDomainEvent
{
    public function __construct(Uuid $aggregate_id, DateTimeImmutable $occurred_on)
    {
        parent::__construct($aggregate_id, $occurred_on);
    }

    public function getEventBody() : string
    {
        //no additional parameters
        return '';
    }

    protected function restoreEventBody(string $event_body) : void
    {
        //no additional parameters
    }

    public static function getEventVersion() : int
    {
        // initial version 1
        return 1;
    }
}
