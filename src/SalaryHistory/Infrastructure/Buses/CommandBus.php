<?php 
namespace Src\SalaryHistory\Infrastructure\Buses;

use Src\Shared\Application\Exceptions\HandlerNotFoundException;

class CommandBus
{
    protected $handlers = [];

    public function register(string $commandClass, string $handlerClass)
    {
        $this->handlers[$commandClass] = $handlerClass;
    }

    public function dispatch($command)
    {
        $handlerClass = $this->handlers[get_class($command)] ?? null;

        if (!$handlerClass) {
            throw new HandlerNotFoundException(get_class($command));
        }

        $handler = app($handlerClass);
        return $handler->handle($command);
    }
}