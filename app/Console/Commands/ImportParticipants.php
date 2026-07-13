<?php

namespace App\Console\Commands;

use App\Enums\Gender;
use App\Models\House;
use App\Models\Participant;
use Illuminate\Console\Command;

class ImportParticipants extends Command
{
    protected $signature = 'import:participants {file=storage/app/public/karnival_sukan.csv}';

    protected $description = 'Import participants from a CSV file';

    public function handle(): int
    {
        $file = $this->argument('file');

        if (! file_exists($file)) {
            $this->error("File not found: {$file}");
            return 1;
        }

        $this->info("Importing participants from {$file}...");

        $imported = 0;
        $skipped = 0;

        if (($handle = fopen($file, 'r')) !== false) {
            // Skip header
            fgetcsv($handle);

            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) < 3) {
                    continue;
                }

                $houseName = trim($row[0]);
                $name = trim($row[1]);
                $genderInput = trim(strtolower($row[2]));

                // Find house by name
                $house = House::where('name', $houseName)->first();
                if (! $house) {
                    $this->warn("House not found: {$houseName}");
                    $skipped++;
                    continue;
                }

                // Map gender
                $gender = $genderInput === 'female' ? Gender::Female : Gender::Male;

                try {
                    Participant::query()->updateOrCreate(
                        ['house_id' => $house->id, 'name' => $name],
                        [
                            'gender' => $gender,
                            'employee_no' => null,
                            'department' => null,
                        ],
                    );
                    $imported++;
                } catch (\Exception $e) {
                    $this->warn("Failed to import {$name}: {$e->getMessage()}");
                    $skipped++;
                }
            }

            fclose($handle);
        }

        $this->info("Import complete: {$imported} imported, {$skipped} skipped");

        return 0;
    }
}
