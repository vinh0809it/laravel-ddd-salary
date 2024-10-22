<?php

namespace Src\Shared\Domain;

interface IQueryHandler
{
    /**
     * @param IQuery $query
     */
    public function handle(IQuery $query): mixed;
}