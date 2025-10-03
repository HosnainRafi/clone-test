<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        
        // Get the Super Admin role ID
        $superAdminRoleId = DB::table('roles')
            ->where('name', 'Super Admin')
            ->value('id');
        
        // Create default admin user
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'email_verified_at' => $now,
                'password' => Hash::make('password'),
                'role_id' => $superAdminRoleId,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        
        DB::table('users')->insert($users);
    }
}
