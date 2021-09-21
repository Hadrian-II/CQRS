<?php

namespace Fluxlabs\CQRS\Event;

use ActiveRecord;
use ilDateTime;
use ilDateTimeException;
use ilException;
use ILIAS\Data\UUID\Uuid;
use ILIAS\Data\UUID\Factory;

/**
 * Class AbstractStoredEvent
 *
 * @author Theodor Truffer <tt@studer-raimann.ch>
 */
abstract class AbstractStoredEvent extends ActiveRecord
{

    /**
     * @var int
     *
     * @con_is_primary true
     * @con_is_unique  true
     * @con_has_field  true
     * @con_fieldtype  integer
     * @con_length     8
     * @con_sequence   true
     */
    protected $id;
    /**
     * @var string
     *
     * @con_has_field  true
     * @con_fieldtype  text
     * @con_index      true
     * @con_is_notnull true
     * @con_length     200
     */
    protected $event_id;
    /**
     * @var int
     *
     * @con_has_field  true
     * @con_fieldtype  integer
     * @con_is_notnull true
     * @con_length     8
     */
    protected $event_version;
    /**
     * @var Uuid
     *
     * @con_has_field  true
     * @con_fieldtype  text
     * @con_length     200
     * @con_index      true
     * @con_is_notnull true
     */
    protected $aggregate_id;
    /**
     * @var string
     *
     * @con_has_field  true
     * @con_fieldtype  text
     * @con_length     200
     * @con_index      true
     * @con_is_notnull true
     */
    protected $event_name;
    /**
     * @var ilDateTime
     *
     * @con_has_field  true
     * @con_fieldtype  timestamp
     * @con_index      true
     * @con_is_notnull true
     */
    protected $occurred_on;
    /**
     * @var int
     *
     * @con_has_field  true
     * @con_fieldtype  integer
     * @con_index      true
     * @con_is_notnull true
     */
    protected $initiating_user_id;
    /**
     * @var string
     *
     * @con_has_field  true
     * @con_fieldtype  clob
     * @con_is_notnull true
     */
    protected $event_body = '';
    /**
     * @var string
     *
     * @con_has_field  true
     * @con_fieldtype  text
     * @con_length     256
     * @con_is_notnull true
     */
    protected $event_class;


    /**
     * Store Event Data
     */
    public function setEventData(
        string $event_id,
        int $event_version,
        Uuid $aggregate_id,
        string $event_name,
        ilDateTime $occurred_on,
        int $initiating_user_id,
        string $event_body,
        string $event_class
    ) : void {
        $this->event_id = $event_id;
        $this->event_version = $event_version;
        $this->aggregate_id = $aggregate_id;
        $this->event_name = $event_name;
        $this->occurred_on = $occurred_on;
        $this->initiating_user_id = $initiating_user_id;
        $this->event_body = $event_body;
        $this->event_class = $event_class;
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getEventClass() : string
    {
        return $this->event_class;
    }

    public function getEventId() : string
    {
        return $this->event_id;
    }

    public function getEventVersion() : int
    {
        return $this->event_version;
    }

    public function getAggregateId() : string
    {
        return $this->aggregate_id;
    }

    public function getEventName() : string
    {
        return $this->event_name;
    }

    public function getOccurredOn() : ilDateTime
    {
        return $this->occurred_on;
    }

    public function getInitiatingUserId() : int
    {
        return $this->initiating_user_id;
    }

    public function getEventBody() : string
    {
        return $this->event_body;
    }

    public function sleep($field_name)
    {
        switch ($field_name) {
            case 'aggregate_id':
                return $this->aggregate_id->toString();
            case 'occurred_on':
                return $this->occurred_on->get(IL_CAL_DATETIME);
            default:
                return null;
        }
    }

    public function wakeUp($field_name, $field_value)
    {
        switch ($field_name) {
            case 'aggregate_id':
                $factory = new Factory();
                return $factory->fromString($field_value);
            case 'occurred_on':
                return new ilDateTime($field_value, IL_CAL_DATETIME);
            default:
                return null;
        }
    }

    //
    // Not supported CRUD-Options:
    //
    /**
     * @throws ilException
     */
    public function store() : void
    {
        throw new ilException("Store is not supported - It's only possible to add new records to this store!");
    }


    /**
     * @throws ilException
     */
    public function update() : void
    {
        throw new ilException("Update is not supported - It's only possible to add new records to this store!");
    }


    /**
     * @throws ilException
     */
    public function delete() : void
    {
        throw new ilException("Delete is not supported - It's only possible to add new records to this store!");
    }


    /**
     * @throws ilException
     */
    public function save() : void
    {
        throw new ilException("Save is not supported - It's only possible to add new records to this store!");
    }
}
