<?php

namespace Fluxlabs\CQRS\Projection;

use Exception;
use ilException;
use ilLogger;
use Fluxlabs\CQRS\Event\EventStore;
use Fluxlabs\CQRS\Projection\ValueObjects\ProjectorPosition;

/**
 * Class Projectionist
 *
 * @package srag\CQRS\Projection
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class Projectionist
{
    protected PositionLedger $position_ledger;

    protected EventStore $event_store;

    protected ilLogger $error_logger;

    public function __construct(PositionLedger $position_ledger, EventStore $event_store, ilLogger $error_logger)
    {
        $this->position_ledger = $position_ledger;
        $this->event_store = $event_store;
        $this->error_logger = $error_logger;
    }

    public function playProjectors(ProjectorCollection $projector_collection) : void
    {
        $exceptions = [];
        $event_handler = new ProjectionEventHandler();
        foreach ($projector_collection->all() as $projector) {
            $position = $this->position_ledger->fetch($projector) ?: ProjectorPosition::makeNewUnplayed($projector);
            if ($position->isFailing()) {
                continue;
            }
            try {
                foreach ($this->event_store->getEventStream($position->last_position)->getEvents() as $event) {
                    $event_handler->handle($event, $projector);
                    $position = $position->played($event);
                }
            } catch (Exception $e) {
                $this->error_logger->error($e->getMessage());
                $this->error_logger->error($e->getTraceAsString());
                $position = $position->broken();
                $exceptions[] = $e;
            }
            $this->position_ledger->store($position);
        }
        if (count($exceptions) > 0) {
            throw new ilException(count($exceptions) . ' projector(s) failed. See Error Log for details.');
        }
    }
}
