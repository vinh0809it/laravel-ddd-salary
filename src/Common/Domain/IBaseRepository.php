<?php

namespace Src\Common\Domain;

interface IBaseRepository
{
    public function tableName();

    public function getQuery();

    public function existsByCond(array $cond);

    public function exists(string $id);

    public function all();

    public function find($id);

    public function create($param = []);

    public function update($id, $param);

    public function updateBy(array $cond, array $param);

    public function delete($id);
}
