<?php

namespace Fluxlabs\CQRS\Projection;

use Exception;

/**
 * Class ProjectorCollection
 *
 * @package srag\CQRS\Projection
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class ProjectorCollection
{
    /**
     * @var Projector[]
     */
    protected array $projectors;

    public function __construct(array $projectors)
    {
        foreach ($projectors as $projector) {
            if (!($projector instanceof Projector)) {
                throw new Exception('Class ' . get_class($projector) . ' cannot be added to ProjectorCollection.');
            }
        }
        $this->projectors = $projectors;
    }

    /**
     * @return Projector[]
     */
    public function all() : array
    {
        return $this->projectors;
    }
}
