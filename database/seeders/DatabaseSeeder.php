<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Src\User\Infrastructure\EloquentModels\UserEloquentModel;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        UserEloquentModel::factory()->admin()->create();
    }
}
