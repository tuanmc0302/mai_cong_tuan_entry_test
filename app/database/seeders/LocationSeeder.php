<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/data/locations.csv');

        if (! is_readable($path)) {
            $this->command?->error("Unable to read file: {$path}");
            return;
        }

        $handle = fopen($path, 'r');
        fgetcsv($handle); // remove the header row: ID, Name, Lat, Lng, Status

        $now  = now();
        $rows = [];

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 5) {
                continue; // remove empty line
            }

            [$id, $name, $lat, $lng, $status] = $row;

            $rows[] = [
                'id'         => (int) $id,
                'name'       => trim($name),
                'lat'        => trim($lat),
                'lng'        => trim($lng),
                'status'     => trim($status) ?: null,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ];
        }

        fclose($handle);

        // Clear everything and reload, so the seeder produces the same result regardless of how many times it is run
        Location::truncate();

        foreach (array_chunk($rows, 500) as $chunk) {
            Location::insert($chunk);
        }

        $this->command?->info(count($rows) . ' locations have been loaded');
    }
}
