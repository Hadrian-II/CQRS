<?php

namespace Fluxlabs\CQRS\Event;

/**
 * Class DomainEventSubscriber
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  Adrian Lüthi <al@studer-raimann.ch>
 * @author  Björn Heyser <bh@bjoernheyser.de>
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
interface DomainEventSubscriber
{
    public function handle(DomainEvent $aDomainEvent) : void;

    public function isSubscribedTo(DomainEvent $aDomainEvent) : bool;
}
