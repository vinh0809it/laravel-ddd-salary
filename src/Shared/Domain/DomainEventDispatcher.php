<?php
namespace Src\Shared\Domain;

interface DomainEventDispatcher 
{
    public function dispatch(object $event): void;
}
