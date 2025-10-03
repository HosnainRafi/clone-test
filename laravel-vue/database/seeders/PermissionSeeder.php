<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        
        // Define default permissions
        $permissions = [
            // Site permissions
            [
                'name' => 'site.view',
                'description' => 'View sites',
                'content' => json_encode(['resource' => 'site', 'action' => 'view']),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'site.create',
                'description' => 'Create sites',
                'content' => json_encode(['resource' => 'site', 'action' => 'create']),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'site.edit',
                'description' => 'Edit sites',
                'content' => json_encode(['resource' => 'site', 'action' => 'edit']),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'site.delete',
                'description' => 'Delete sites',
                'content' => json_encode(['resource' => 'site', 'action' => 'delete']),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            
            // Theme permissions
            [
                'name' => 'theme.view',
                'description' => 'View themes',
                'content' => json_encode(['resource' => 'theme', 'action' => 'view']),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'theme.create',
                'description' => 'Create themes',
                'content' => json_encode(['resource' => 'theme', 'action' => 'create']),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'theme.edit',
                'description' => 'Edit themes',
                'content' => json_encode(['resource' => 'theme', 'action' => 'edit']),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'theme.delete',
                'description' => 'Delete themes',
                'content' => json_encode(['resource' => 'theme', 'action' => 'delete']),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            
            // Component permissions
            [
                'name' => 'component.view',
                'description' => 'View components',
                'content' => json_encode(['resource' => 'component', 'action' => 'view']),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'component.create',
                'description' => 'Create components',
                'content' => json_encode(['resource' => 'component', 'action' => 'create']),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'component.edit',
                'description' => 'Edit components',
                'content' => json_encode(['resource' => 'component', 'action' => 'edit']),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'component.delete',
                'description' => 'Delete components',
                'content' => json_encode(['resource' => 'component', 'action' => 'delete']),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            
            // User permissions
            [
                'name' => 'user.view',
                'description' => 'View users',
                'content' => json_encode(['resource' => 'user', 'action' => 'view']),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'user.create',
                'description' => 'Create users',
                'content' => json_encode(['resource' => 'user', 'action' => 'create']),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'user.edit',
                'description' => 'Edit users',
                'content' => json_encode(['resource' => 'user', 'action' => 'edit']),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'user.delete',
                'description' => 'Delete users',
                'content' => json_encode(['resource' => 'user', 'action' => 'delete']),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
        
        DB::table('permissions')->insert($permissions);
    }
}
