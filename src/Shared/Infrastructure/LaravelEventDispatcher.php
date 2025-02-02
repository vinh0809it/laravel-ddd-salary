<?php
namespace Src\Shared\Infrastructure;

use Src\Shared\Domain\DomainEventDispatcher;

class LaravelEventDispatcher implements DomainEventDispatcher
{
    public function dispatch(object $event): void
    {
        event($event);
    }
}