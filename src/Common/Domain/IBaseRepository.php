<?php

namespace Src\Common\Domain;

use Illuminate\Database\Eloquent\Builder;

interface IBaseRepository
{
    public function tableName();

    public function getQuery();

    public function isExisted(array $cond);

    public function all();

    public function find($id);

    public function create($param = []);

    public function update($id, $param);

    public function updateBy(array $cond, array $param);

    public function delete($id);
}
