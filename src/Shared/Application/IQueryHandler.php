<?php

namespace Src\Shared\Application;

interface IQueryHandler
{
    /**
     * @param IQuery $query
     */
    public function handle(IQuery $query): mixed;
}