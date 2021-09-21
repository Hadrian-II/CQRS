<?php

namespace Fluxlabs\CQRS\Command;

use ILIAS\Data\Result;
use ILIAS\Data\Result\Error;
use Fluxlabs\CQRS\Exception\CQRSException;

/**
 * Class CommandBus
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class CommandBus implements CommandBusContract
{
    private array $middlewares;

    /**
     * @var string[]
     */
    private $command_handler_map;

    public function __construct()
    {
        $this->middlewares = [];
    }

    public function handle(CommandContract $command) : Result
    {
        $class = get_class($command);

        if (!array_key_exists($class, $this->command_handler_map)) {
            return new Error(new CQRSException(sprintf('No handler defined for command: %s', $class)));
        }

        /** @var $config CommandConfiguration */
        $config = $this->command_handler_map[$class];

        foreach ($this->middlewares as $middleware) {
            $command = $middleware->handle($command);
        }

        if (!$config->getAccess()->canIssueCommand($command)) {
            return new Error(new CQRSException('Access Denied'));
        }

        return $config->getHandler()->handle($command);
    }


    /**
     * Appends new middleware for this message bus.
     * Should only be used at configuration time.
     */
    public function appendMiddleware(CommandHandlerMiddleware $middleware) : void
    {
        $this->middlewares[] = $middleware;
    }

    public function registerCommand(CommandConfiguration $config)
    {
        $this->command_handler_map[$config->getClass()] = $config;
    }
}
