<?php

namespace Src\Shared\Application;

interface ICommandHandler
{
    /**
     * @param ICommand $command
     * 
     * @return mixed
     */
    public function handle(ICommand $command);
}