<?php
/* Copyright (c) 2019 Extended GPL, see docs/LICENSE */

namespace Fluxlabs\CQRS\Event\Standard;

use Fluxlabs\CQRS\Event\AbstractDomainEvent;
use Fluxlabs\CQRS\Aggregate\AbstractValueObject;
use ILIAS\Data\UUID\Uuid;
use ilDateTime;

/**
 * Class AggregateCreatedEvent
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class AggregateCreatedEvent extends AbstractDomainEvent
{
    protected ?array $additional_data;

    public function __construct(
        Uuid $aggregate_id,
        ilDateTime $occurred_on,
        ?array $additional_data = null)
    {
        $this->additional_data = $additional_data;

        parent::__construct($aggregate_id, $occurred_on);
    }

    public function getAdditionalData() : ?array
    {
        return $this->additional_data;
    }

    public function getEventBody() : string
    {
        return json_encode($this->additional_data);
    }

    protected function restoreEventBody(string $event_body) : void
    {
        $this->additional_data = AbstractValueObject::deserialize($event_body);
    }

    public static function getEventVersion() : int
    {
        // initial version 1
        return 1;
    }
}
