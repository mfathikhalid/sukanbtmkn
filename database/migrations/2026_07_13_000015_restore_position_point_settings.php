<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('point_settings')->whereNotIn('position', [1, 2, 3, 4])->delete();

        foreach ([1 => 10, 2 => 7, 3 => 5, 4 => 3] as $position => $points) {
            DB::table('point_settings')->updateOrInsert(
                ['position' => $position],
                ['points' => $points, 'updated_at' => now(), 'created_at' => now()],
            );
        }
    }

    public function down(): void
    {
        // These required scoring records should not be removed during rollback.
    }
};
