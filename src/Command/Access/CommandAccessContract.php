<?php

namespace Fluxlabs\CQRS\Command\Access;

use Fluxlabs\CQRS\Command\CommandContract;

/**
 * Interface CommandConfiguration
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
interface CommandAccessContract
{
    /**
     * Method to check if the use can perform the command
     * Returns true if access granted, false if not
     */
    public function canIssueCommand(CommandContract $command) : bool;
}
