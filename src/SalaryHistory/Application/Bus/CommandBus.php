<?php 
namespace Src\SalaryHistory\Application\Bus;

use Src\Shared\Application\Exceptions\HandlerNotFoundException;
use Src\Shared\Application\ICommand;
use Src\Shared\Application\ICommandBus;
use Src\Shared\Application\ICommandHandler;

class CommandBus implements ICommandBus
{
    protected $handlers = [];

    public function register(string $commandClass, ICommandHandler $handler): void
    {
        $this->handlers[$commandClass] = $handler;
    }

    public function dispatch(ICommand $command)
    {
        $commandClass = get_class($command);

        if (!isset($this->handlers[$commandClass])) {
            throw new HandlerNotFoundException($commandClass);
        }

        return $this->handlers[$commandClass]->handle($command);
    }
}