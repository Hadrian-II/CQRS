<?php
declare(strict_types=1);

namespace Fluxlabs\CQRS\Event\Standard;

use DateTimeImmutable;
use Fluxlabs\CQRS\Aggregate\RevisionId;
use Fluxlabs\CQRS\Event\AbstractDomainEvent;
use ILIAS\Data\UUID\Uuid;

/**
 * Class AggregateRevisionCreatedEvent
 *
 * @license Extended GPL, see docs/LICENSE
 * @copyright 1998-2020 ILIAS open source
 *
 * @package srag/asq
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 */
class AggregateRevisionCreatedEvent extends AbstractDomainEvent
{
    public ?RevisionId $revision_id;

    public function __construct(
        Uuid $aggregate_id,
        DateTimeImmutable $occurred_on,
        RevisionId $revision_id = null)
    {
        $this->revision_id = $revision_id;

        parent::__construct($aggregate_id, $occurred_on);
    }

    public function getRevisionId() : RevisionId
    {
        return $this->revision_id;
    }

    public function getEventBody() : string
    {
        return json_encode($this->revision_id);
    }

    public function restoreEventBody(string $json_data) : void
    {
        $this->revision_id = RevisionId::deserialize($json_data);
    }

    public static function getEventVersion() : int
    {
        // initial version 1
        return 1;
    }
}
