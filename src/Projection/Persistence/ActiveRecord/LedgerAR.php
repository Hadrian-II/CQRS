<?php

namespace Fluxlabs\CQRS\Projection\Persistence\ActiveRecord;

use ActiveRecord;
use DateTimeImmutable;
use Fluxlabs\CQRS\Projection\ValueObjects\ProjectorStatus;

/**
 * Class LedgerAR
 *
 * @author Theodor Truffer <tt@studer-raimann.ch>
 */
class LedgerAR extends ActiveRecord
{
    const TABLE_NAME = 'sr_projection_ledger';

    public function getConnectorContainerName() : string
    {
        return self::TABLE_NAME;
    }

    /**
     * @var string
     *
     * @con_is_primary true
     * @con_has_field  true
     * @con_fieldtype  text
     * @con_length     200
     */
    public $projector_class;

    /**
     * @var int
     *
     * @con_has_field  true
     * @con_fieldtype  integer
     * @con_is_notnull true
     * @con_length     8
     */
    public $processed_events;

    /**
     * @var string
     *
     * @con_has_field  true
     * @con_fieldtype  text
     * @con_length     200
     */
    public $last_position;

    /**
     * @var DateTimeImmutable
     *
     * @con_has_field  true
     * @con_fieldtype  timestamp
     */
    public $occurred_at;

    /**
     * @var ProjectorStatus
     *
     * @con_has_field  true
     * @con_fieldtype  text
     * @con_is_notnull true
     * @con_length     200
     */
    public $status;

    public function sleep($field_name)
    {
        switch ($field_name) {
            case 'last_position':
                return $this->last_position ? $this->last_position->getId() : null;
            case 'occurred_at':
                return $this->occurred_at ? $this->occurred_at->getTimestamp() : null;
            case 'status':
                return $this->status ? $this->status->__toString() : null;
            default:
                return null;
        }
    }

    public function wakeUp($field_name, $field_value)
    {
        switch ($field_name) {
            case 'occurred_at':
                return $field_value ? (new DateTimeImmutable())->setTimestamp($field_value) : null;
            case 'status':
                return new ProjectorStatus($field_value);
            default:
                return null;
        }
    }
}
