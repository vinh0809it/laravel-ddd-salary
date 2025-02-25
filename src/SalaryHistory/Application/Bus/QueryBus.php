<?php
namespace Src\SalaryHistory\Application\Bus;

use Src\Shared\Application\Exceptions\HandlerNotFoundException;
use Src\Shared\Application\IQuery;
use Src\Shared\Application\IQueryBus;
use Src\Shared\Application\IQueryHandler;

class QueryBus implements IQueryBus
{
    private array $handlers = [];

    /**
     * @param string $queryClass
     * @param IQueryHandler $handler
     * 
     * @return void
     */
    public function register(string $queryClass, IQueryHandler $handler): void
    {
        $this->handlers[$queryClass] = $handler;
    }

    /**
     * @param IQuery $query
     * 
     * @return mixed
     */
    public function dispatch(IQuery $query): mixed
    {
        $queryClass = get_class($query);

        if (!isset($this->handlers[$queryClass])) {
            throw new HandlerNotFoundException($queryClass);
        }

        return $this->handlers[$queryClass]->handle($query);
    }
}