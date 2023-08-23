<?php

namespace Database\Seeders;

use App\Models\Master\RoleModel;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        RoleModel::factory(10)->create();
        User::factory(10)->create();
    }
}
