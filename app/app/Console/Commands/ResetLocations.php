<?php

namespace App\Console\Commands;

use Database\Seeders\LocationSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ResetLocations extends Command
{
    protected $signature = 'locations:reset';

    protected $description = 'Reset the locations table to its original data from the CSV file';

    public function handle(): int
    {
        $this->info('Resetting the locations table...');

        $exitCode = $this->call('db:seed', [
            '--class' => LocationSeeder::class,
            '--force' => true, // skip confirmation prompt when APP_ENV=production
        ]);

        if ($exitCode !== self::SUCCESS) {
            Log::error('locations:reset failed.');
            $this->error('Reset failed.');

            return self::FAILURE;
        }

        Log::info('locations:reset complete.');
        $this->info('Complete.');

        return self::SUCCESS;
    }
}
