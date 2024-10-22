<?php

namespace Src\Shared\Application;

interface ICommandBus
{
    public function register(string $commandClass, ICommandHandler $handler): void;
    public function dispatch(ICommand $command);
}