<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        
        // Define default roles
        $roles = [
            [
                'name' => 'Super Admin',
                'description' => 'Has full access to all system features',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Admin',
                'description' => 'Can manage sites, themes, and components',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Editor',
                'description' => 'Can edit content on sites',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'User',
                'description' => 'Standard user with limited permissions',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        
        DB::table('roles')->insert($roles);
    }
}
