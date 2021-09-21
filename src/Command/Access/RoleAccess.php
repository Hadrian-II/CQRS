<?php

namespace Fluxlabs\CQRS\Command\Access;

use Fluxlabs\CQRS\Command\CommandContract;

/**
 * Class RoleAccess
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class RoleAccess implements CommandAccessContract
{
    /**
     * @var int[]
     */
    private array $allowed_roles;
    
    /**
     * @param array $roles  Roles that are allowed to perform command
     */
    public function __create(array $roles)
    {
        $this->allowed_roles = $roles;
    }

    public function canIssueCommand(CommandContract $command) : bool
    {
        global $DIC;
        
        $user_roles = $DIC->rbac()->review()->assignedRoles($command->getIssuingUserId());
        
        // One of the users roles is allowed to perform command
        return count(array_intersect($user_roles, $this->allowed_roles)) > 0;
    }
}
