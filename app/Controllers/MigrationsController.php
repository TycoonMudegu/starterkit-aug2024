<?php
namespace App\Controllers;

use App\Migrations\MigrationsTest;

class MigrationsController
{
    public function TestMigration(): bool
    {
        // Instantiate MigrationsTest directly (no DB passed)
        $migrationsTest = new MigrationsTest();

        // Run migration and seeding
        return $migrationsTest->runMigrationAndSeed(10);
    }
}
