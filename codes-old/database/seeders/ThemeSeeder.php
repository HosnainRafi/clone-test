<?php

namespace Database\Seeders;

use App\Models\Theme;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $themes = [
            [
                'name' => 'MBSTU Default Theme',
                'description' => 'Default theme for MBSTU websites',
                'primary_color' => '#1e40af',
                'secondary_color' => '#059669',
                'font_family' => 'Inter, sans-serif',
                'is_active' => true,
                'content' => [
                    'websiteTitle' => 'Mawlana Bhashani Science and Technology University',
                    'bannerTitle' => 'Excellence in Science and Technology Education',
                    'bannerSubtitle' => 'Shaping the future through innovation and research',
                    'version' => '1.0.0',
                    'accentColor' => '#dc2626',
                    'footerCopyright' => '© 2024 Mawlana Bhashani Science and Technology University. All rights reserved.',
                    'footerContact' => [
                        'address' => 'Santosh, Tangail-1902, Bangladesh',
                        'phone' => '+880-2-9291234',
                        'email' => 'info@mbstu.ac.bd'
                    ],
                    'navItems' => [
                        ['label' => 'Home', 'url' => '/'],
                        ['label' => 'About', 'url' => '/about'],
                        ['label' => 'Academics', 'url' => '/academics'],
                        ['label' => 'Research', 'url' => '/research'],
                        ['label' => 'Contact', 'url' => '/contact']
                    ]
                ],
                'settings' => [
                    'layout' => 'default',
                    'headerStyle' => 'modern',
                    'footerStyle' => 'extended'
                ],
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Department Theme',
                'description' => 'Theme for department websites',
                'primary_color' => '#059669',
                'secondary_color' => '#1e40af',
                'font_family' => 'Inter, sans-serif',
                'is_active' => true,
                'content' => [
                    'websiteTitle' => 'Department - MBSTU',
                    'bannerTitle' => 'Academic Excellence',
                    'bannerSubtitle' => 'Advancing knowledge and innovation',
                    'version' => '1.0.0',
                    'accentColor' => '#dc2626',
                    'footerCopyright' => '© 2024 MBSTU Department. All rights reserved.',
                    'footerContact' => [
                        'address' => 'Santosh, Tangail-1902, Bangladesh',
                        'phone' => '+880-2-9291234',
                        'email' => 'dept@mbstu.ac.bd'
                    ]
                ],
                'settings' => [
                    'layout' => 'department',
                    'headerStyle' => 'compact',
                    'footerStyle' => 'minimal'
                ],
                'created_by' => 1,
                'updated_by' => 1,
            ]
        ];

        foreach ($themes as $themeData) {
            Theme::create($themeData);
        }

        $this->command->info('Themes seeded successfully!');
    }
}
