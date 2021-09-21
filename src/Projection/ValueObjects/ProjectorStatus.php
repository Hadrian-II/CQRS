<?php

namespace Fluxlabs\CQRS\Projection\ValueObjects;

use Exception;
use Fluxlabs\CQRS\Aggregate\AbstractValueObject;

/**
 * Class ProjectorStatus
 *
 * @author Theodor Truffer <tt@studer-raimann.ch>
 */
class ProjectorStatus extends AbstractValueObject
{
    const NEW = "new";
    const WORKING = "working";
    const BROKEN = "broken";
    const STALLED = "stalled";

    private string $value;

    public function __construct(string $value)
    {
        if (!in_array($value, [self::NEW, self::WORKING, self::BROKEN, self::STALLED])) {
            throw new Exception("Unknown status of '$value'");
        }
        $this->value = $value;
    }

    public function is(string $value) : bool
    {
        return $this->value == $value;
    }

    public function __toString() : string
    {
        return $this->value;
    }

    public static function new() : ProjectorStatus
    {
        return new self(self::NEW);
    }

    public static function working() : ProjectorStatus
    {
        return new self(self::WORKING);
    }

    public static function broken() : ProjectorStatus
    {
        return new self(self::BROKEN);
    }

    public static function stalled() : ProjectorStatus
    {
        return new self(self::STALLED);
    }
}
