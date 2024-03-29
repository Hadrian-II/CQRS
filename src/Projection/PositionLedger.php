<?php

namespace Fluxlabs\CQRS\Projection;

use Fluxlabs\CQRS\Projection\ValueObjects\ProjectorPosition;

/**
 * Class PositionLedger
 *
 * @package srag\CQRS\Projection
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
interface PositionLedger
{
    public function store(ProjectorPosition $position) : void;

    public function fetch(Projector $projector) : ?ProjectorPosition;
}
