<?php

namespace Src\Common\Infrastructure;

use Src\Common\Domain\IBaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements IBaseRepository
{
    // Model property on class instances
    protected $model;

    /** @var Builder */
    protected $query;

    // Constructor to bind model to repo
    public function __construct()
    {
        $this->setModel();
        $this->query = $this->model->newQuery();
    }

    // Get the associated model
    abstract public function getModel();

    // Set the associated model
    public function setModel()
    {
        $this->model = app()->make($this->getModel());
    }

    public function getQuery()
    {
        return $this->query->getQuery();
    }

    public function tableName()
    {
        return $this->model->getTable();
    }

    public function existsByCond(array $cond)
    {
        return $this->query->where($cond)->exists();
    }

    public function exists(string $id): bool
    {
        return $this->query->where("id", $id)->exists();
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function first()
    {
        return $this->model->first();
    }

    public function update($id, $param)
    {
        $this->model->find($id)->update($param);
    }

    public function updateBy(array $cond, array $param)
    {
        $this->model->where($cond)->update($param);
    }

    public function delete($id)
    {
        $this->model->find($id)->delete();
    }

    public function create($param = [])
    {
        return $this->model->create($param);
    }

    public function getPagination(LengthAwarePaginator $result): array
    {
        return  [
            'current_page' => $result->currentPage(),
            'last_page' => $result->lastPage(),
            'per_page' => $result->perPage(),
            'total' => $result->total(),
            'from' => $result->firstItem(),
            'to' => $result->lastItem()
        ];
    }
}
