<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateSiteTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Update main university site
        DB::table('sites')
            ->where('domain', 'localhost')
            ->update(['site_type' => 'university']);

        echo "✓ Updated main university site\n";

        // Update department sites (subdomains)
        DB::table('sites')
            ->where('domain', 'like', '%.localhost')
            ->where('domain', '!=', 'localhost')
            ->update(['site_type' => 'department']);

        echo "✓ Updated department sites\n";

        // You can manually set specific sites to 'faculty' type if needed
        // DB::table('sites')->where('id', X)->update(['site_type' => 'faculty']);

        echo "\nSite types updated successfully!\n";

        // Show updated sites
        $sites = DB::table('sites')->select('id', 'name', 'domain', 'site_type')->get();

        echo "\nCurrent sites:\n";
        echo str_repeat('-', 80) . "\n";
        printf("%-5s %-30s %-30s %-15s\n", 'ID', 'Name', 'Domain', 'Site Type');
        echo str_repeat('-', 80) . "\n";

        foreach ($sites as $site) {
            printf("%-5s %-30s %-30s %-15s\n",
                $site->id,
                substr($site->name, 0, 30),
                substr($site->domain, 0, 30),
                $site->site_type
            );
        }

        echo str_repeat('-', 80) . "\n";
    }
}
