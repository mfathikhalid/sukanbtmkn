<?php

namespace Database\Seeders;

use App\Enums\SportType;
use App\Models\House;
use App\Models\PointSetting;
use App\Models\Sport;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@sukanbtmkn.test'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ],
        );

        foreach ([
            ['name' => 'Merah', 'color' => '#dc2626'],
            ['name' => 'Hijau', 'color' => '#16a34a'],
            ['name' => 'Biru', 'color' => '#2563eb'],
            ['name' => 'Kuning', 'color' => '#facc15'],
        ] as $house) {
            House::query()->updateOrCreate(['name' => $house['name']], $house);
        }

        foreach ([
            ['position' => 1, 'points' => 10],
            ['position' => 2, 'points' => 7],
            ['position' => 3, 'points' => 5],
            ['position' => 4, 'points' => 3],
        ] as $pointSetting) {
            PointSetting::query()->updateOrCreate(['position' => $pointSetting['position']], $pointSetting);
        }

        foreach ([
            ['name' => 'FIFA', 'type' => SportType::League, 'gender_based' => true, 'male_quota' => 2, 'female_quota' => 0],
            ['name' => 'Tekken', 'type' => SportType::League, 'gender_based' => true, 'male_quota' => 0, 'female_quota' => 2],
            ['name' => 'Pickleball', 'type' => SportType::League, 'gender_based' => true, 'male_quota' => 2, 'female_quota' => 2],
            ['name' => 'Congkak', 'type' => SportType::League, 'gender_based' => true, 'male_quota' => 3, 'female_quota' => 3],
            ['name' => 'Carrom', 'type' => SportType::League, 'gender_based' => true, 'male_quota' => 2, 'female_quota' => 2],
            ['name' => 'Dart', 'type' => SportType::League, 'gender_based' => true, 'male_quota' => 3, 'female_quota' => 3],
            ['name' => 'Bowling', 'type' => SportType::Bowling, 'gender_based' => true, 'male_quota' => 2, 'female_quota' => 2],
        ] as $sport) {
            Sport::query()->updateOrCreate(['name' => $sport['name']], $sport);
        }
    }
}
