<?php

namespace Fluxlabs\CQRS\Projection\ValueObjects;

use Exception;
use ilDateTime;
use Fluxlabs\CQRS\Aggregate\AbstractValueObject;
use Fluxlabs\CQRS\Event\DomainEvent;
use Fluxlabs\CQRS\Projection\Projector;

/**
 * Class ProjectorPosition
 *
 * @package srag\CQRS\Projection\ValueObjects
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class ProjectorPosition extends AbstractValueObject
{
    public Projector $projector;

    public int $processed_events;

    public string $last_position;

    public ilDateTime $occurred_at;

    public ProjectorStatus $status;

    public function __construct(
        Projector $projector,
        int $processed_events,
        ?ilDateTime $occurred_at,
        ?string $last_position,
        ProjectorStatus $status
    ) {
        $this->projector = $projector;
        $this->processed_events = $processed_events;
        $this->last_position = $last_position;
        $this->occurred_at = $occurred_at;
        $this->status = $status;
    }

    public static function makeNewUnplayed(Projector $projector) : ProjectorPosition
    {
        return new ProjectorPosition(
            $projector,
            0,
            null,
            null,
            ProjectorStatus::new()
        );
    }

    public function played(DomainEvent $event) : ProjectorPosition
    {
        $event_count = $this->processed_events + 1;

        return new ProjectorPosition(
            $this->projector,
            $event_count,
            new ilDateTime(time(), IL_CAL_UNIX),
            $event->getEventId(),
            ProjectorStatus::working()
        );
    }

    public function broken() : ProjectorPosition
    {
        return new ProjectorPosition(
            $this->projector,
            $this->processed_events,
            new ilDateTime(time(), IL_CAL_UNIX),
            $this->last_position,
            ProjectorStatus::broken()
        );
    }

    public function fixed() : ProjectorPosition
    {
        return new ProjectorPosition(
            $this->projector,
            $this->processed_events,
            new ilDateTime(time(), IL_CAL_UNIX),
            $this->last_position,
            ProjectorStatus::working()
        );
    }

    public function stalled() : ProjectorPosition
    {
        return new ProjectorPosition(
            $this->projector,
            $this->processed_events,
            new ilDateTime(time(), IL_CAL_UNIX),
            $this->last_position,
            ProjectorStatus::stalled()
        );
    }

    public function isSame(Projector $current_projector)
    {
        return $this->projector->equals($current_projector);
    }

    public function isFailing()
    {
        return $this->status->is(ProjectorStatus::BROKEN) || $this->status->is(ProjectorStatus::STALLED);
    }
}
