<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        
        // Get admin user ID for created_by
        $adminId = DB::table('users')
            ->where('email', 'admin@example.com')
            ->value('id');
        
        // Define default themes
        $themes = [
            [
                'name' => 'Default Theme',
                'description' => 'Default theme for MBSTU sites',
                'primary_color' => '#4F46E5',
                'secondary_color' => '#3B82F6',
                'font_family' => 'Figtree, sans-serif',
                'settings' => json_encode([
                    'header' => [
                        'height' => '80px',
                        'background' => '#ffffff',
                        'textColor' => '#333333'
                    ],
                    'footer' => [
                        'height' => '200px',
                        'background' => '#333333',
                        'textColor' => '#ffffff'
                    ]
                ]),
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' => $adminId,
                'updated_by' => $adminId,
            ],
            [
                'name' => 'ICT Theme',
                'description' => 'Theme for ICT department',
                'primary_color' => '#E53E3E',
                'secondary_color' => '#F56565',
                'font_family' => 'Figtree, sans-serif',
                'settings' => json_encode([
                    'header' => [
                        'height' => '80px',
                        'background' => '#ffebeb',
                        'textColor' => '#333333'
                    ],
                    'footer' => [
                        'height' => '200px',
                        'background' => '#333333',
                        'textColor' => '#ffffff'
                    ]
                ]),
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' => $adminId,
                'updated_by' => $adminId,
            ],
            [
                'name' => 'CSE Theme',
                'description' => 'Theme for CSE department',
                'primary_color' => '#38A169',
                'secondary_color' => '#48BB78',
                'font_family' => 'Figtree, sans-serif',
                'settings' => json_encode([
                    'header' => [
                        'height' => '80px',
                        'background' => '#ebffeb',
                        'textColor' => '#333333'
                    ],
                    'footer' => [
                        'height' => '200px',
                        'background' => '#333333',
                        'textColor' => '#ffffff'
                    ]
                ]),
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' => $adminId,
                'updated_by' => $adminId,
            ],
        ];
        
        DB::table('themes')->insert($themes);
    }
}
