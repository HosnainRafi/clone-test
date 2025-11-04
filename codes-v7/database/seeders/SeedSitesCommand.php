<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeedSitesCommand extends Seeder
{
    /**
     * Run the database seeds for sites only.
     */
    public function run(): void
    {
        // Truncate existing data (optional)
        if ($this->command->confirm('Do you want to truncate existing sites data?', false)) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('sites')->truncate();
            DB::table('themes')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->command->info('Existing data truncated.');
        }

        // Run seeders
        $this->call([
            ThemeSeeder::class,
            SiteSeeder::class,
        ]);

        $this->command->info('Sites and themes seeded successfully!');
    }
}
