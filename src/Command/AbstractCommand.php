<?php
/* Copyright (c) 2019 - Martin Studer <ms@studer-raimann.ch> - Extended GPL, see LICENSE */

namespace Fluxlabs\CQRS\Command;

/**
 * Class AbstractCommand
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
abstract class AbstractCommand implements CommandContract
{
    protected int $issuing_user_id;

    public function __construct()
    {
        global $DIC;

        $this->issuing_user_id = $DIC->user()->getId();
    }

    public function getIssuingUserId() : int
    {
        return $this->issuing_user_id;
    }
}
