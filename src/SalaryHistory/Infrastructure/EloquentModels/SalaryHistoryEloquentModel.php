<?php

namespace Src\SalaryHistory\Infrastructure\EloquentModels;

use Illuminate\Database\Eloquent\Model;

class SalaryHistoryEloquentModel extends Model
{
    protected $table = 'salary_histories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'on_date',
        'salary',
        'note'
    ];
}
