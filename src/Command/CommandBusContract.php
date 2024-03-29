<?php
/* Copyright (c) 2019 - Martin Studer <ms@studer-raimann.ch> - Extended GPL, see LICENSE */

namespace Fluxlabs\CQRS\Command;

use ILIAS\Data\Result;

/**
 * Interface CommandBusContract
 *
 * The Command Bus is used to dispatch a given Command into the Bus
 * and maps a Command to a Command Handler.
 *
 */
interface CommandBusContract
{
    public function handle(CommandContract $command) : Result;


    /**
     * Appends new middleware for this message bus.
     * Should only be used at configuration time.
     */
    public function appendMiddleware(CommandHandlerMiddleware $middleware) : void;
}
