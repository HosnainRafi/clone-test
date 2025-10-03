<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Important to call seeders in proper order due to dependencies
        
        // // First create roles and permissions
        // $this->call(RoleSeeder::class);
        // $this->call(PermissionSeeder::class);
        
        // // Then create users who depend on roles
        // $this->call(UserSeeder::class);
        
        // // Then create themes and sites that depend on users (for created_by)
        // $this->call(ThemeSeeder::class);
        // $this->call(SiteSeeder::class);
        
        // For development, we could also seed role-permission relationships
        // $this->call(RolePermissionSeeder::class);
    }
}
