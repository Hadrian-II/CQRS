<?php

namespace srag\CQRS\Projection;

/**
 * Class Projector
 *
 * @author Theodor Truffer <tt@studer-raimann.ch>
 */
abstract class Projector
{
    protected Projection $projection;

    public function __construct(Projection $projection)
    {
        $this->projection = $projection;
    }
}
