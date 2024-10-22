<?php 
namespace Src\SalaryHistory\Application\Bus;

use Src\Shared\Application\Exceptions\HandlerNotFoundException;
use Src\Shared\Application\ICommand;
use Src\Shared\Application\ICommandBus;
use Src\Shared\Application\ICommandHandler;

class CommandBus implements ICommandBus
{
    protected $handlers = [];

    /**
     * @param string $commandClass
     * @param ICommandHandler $handler
     * 
     * @return void
     */
    public function register(string $commandClass, ICommandHandler $handler): void
    {
        $this->handlers[$commandClass] = $handler;
    }

    /**
     * @param ICommand $command
     * 
     */
    public function dispatch(ICommand $command)
    {
        $commandClass = get_class($command);

        if (!isset($this->handlers[$commandClass])) {
            throw new HandlerNotFoundException($commandClass);
        }

        return $this->handlers[$commandClass]->handle($command);
    }
}