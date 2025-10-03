<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SiteSeeder extends Seeder
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
        
        // Get theme IDs
        $defaultThemeId = DB::table('themes')
            ->where('name', 'Default Theme')
            ->value('id');
        
        $ictThemeId = DB::table('themes')
            ->where('name', 'ICT Theme')
            ->value('id');
        
        $cseThemeId = DB::table('themes')
            ->where('name', 'CSE Theme')
            ->value('id');
        
        // Define default sites
        $sites = [
            [
                'name' => 'MBSTU Main Site',
                'description' => 'Main website for Mawlana Bhashani Science and Technology University',
                'domain' => 'mbstu.localhost',
                'theme_id' => $defaultThemeId,
                'settings' => json_encode([
                    'logo' => 'mbstu-logo.png',
                    'favicon' => 'favicon.ico',
                    'meta' => [
                        'title' => 'MBSTU - Mawlana Bhashani Science and Technology University',
                        'description' => 'Official website of Mawlana Bhashani Science and Technology University',
                        'keywords' => 'MBSTU, university, education, science, technology'
                    ]
                ]),
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' => $adminId,
                'updated_by' => $adminId,
            ],
            [
                'name' => 'ICT Department',
                'description' => 'Website for the ICT Department',
                'domain' => 'ict.mbstu.localhost',
                'subdomain' => 'ict',
                'theme_id' => $ictThemeId,
                'settings' => json_encode([
                    'logo' => 'ict-logo.png',
                    'favicon' => 'favicon.ico',
                    'meta' => [
                        'title' => 'ICT Department - MBSTU',
                        'description' => 'Information & Communication Technology Department at MBSTU',
                        'keywords' => 'ICT, information technology, communication technology, department, MBSTU'
                    ]
                ]),
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' => $adminId,
                'updated_by' => $adminId,
            ],
            [
                'name' => 'CSE Department',
                'description' => 'Website for the CSE Department',
                'domain' => 'cse.mbstu.localhost',
                'subdomain' => 'cse',
                'theme_id' => $cseThemeId,
                'settings' => json_encode([
                    'logo' => 'cse-logo.png',
                    'favicon' => 'favicon.ico',
                    'meta' => [
                        'title' => 'CSE Department - MBSTU',
                        'description' => 'Computer Science & Engineering Department at MBSTU',
                        'keywords' => 'CSE, computer science, engineering, department, MBSTU'
                    ]
                ]),
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' => $adminId,
                'updated_by' => $adminId,
            ],
        ];
        
        DB::table('sites')->insert($sites);
    }
}
