<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        User::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
        $this->call([
            PermissionSeeder::class,
            SuperAdminSeeder::class,
            RoleSeeder::class,
        ]);
    }
}
