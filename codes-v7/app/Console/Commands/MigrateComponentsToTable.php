<?php

namespace App\Console\Commands;

use App\Services\ComponentService;
use App\Models\Site;
use Illuminate\Console\Command;

class MigrateComponentsToTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'components:migrate {--site-id= : Specific site ID to migrate} {--force : Force migration even if components exist}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate component data from sites.settings to components table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $componentService = new ComponentService();
        
        $siteId = $this->option('site-id');
        $force = $this->option('force');
        
        if ($siteId) {
            // Migrate specific site
            $site = Site::find($siteId);
            if (!$site) {
                $this->error("Site with ID {$siteId} not found.");
                return 1;
            }
            
            $this->info("Migrating components for site: {$site->name} (ID: {$siteId})");
            
            if (!$force && $site->siteComponents()->count() > 0) {
                $this->warn("Site already has components. Use --force to override.");
                return 1;
            }
            
            $migratedCount = $componentService->migrateFromSiteSettings($siteId);
            $this->info("Migrated {$migratedCount} components for site {$siteId}");
            
        } else {
            // Migrate all sites
            $sites = Site::all();
            $this->info("Found {$sites->count()} sites to migrate");
            
            $totalMigrated = 0;
            foreach ($sites as $site) {
                $this->info("Migrating site: {$site->name} (ID: {$site->id})");
                
                if (!$force && $site->siteComponents()->count() > 0) {
                    $this->warn("Site {$site->id} already has components. Skipping...");
                    continue;
                }
                
                try {
                    $migratedCount = $componentService->migrateFromSiteSettings($site->id);
                    $totalMigrated += $migratedCount;
                    $this->info("Migrated {$migratedCount} components for site {$site->id}");
                } catch (\Exception $e) {
                    $this->error("Error migrating site {$site->id}: " . $e->getMessage());
                }
            }
            
            $this->info("Total components migrated: {$totalMigrated}");
        }
        
        return 0;
    }
}