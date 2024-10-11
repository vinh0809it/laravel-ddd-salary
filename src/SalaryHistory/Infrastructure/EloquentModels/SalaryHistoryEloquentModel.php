<?php

namespace Src\SalaryHistory\Infrastructure\EloquentModels;

use Database\Factories\SalaryHistory\SalaryHistoryEloquentModelFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryHistoryEloquentModel extends Model
{
    use HasFactory;
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

    protected static function newFactory()
    {
        return SalaryHistoryEloquentModelFactory::new();
    }
}
