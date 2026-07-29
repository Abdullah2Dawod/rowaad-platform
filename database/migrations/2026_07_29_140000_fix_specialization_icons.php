<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Two Solar icon slugs return empty SVGs on iconify.design → replace with working alternatives.
        $fixes = [
            'megaphone-bold-duotone' => 'volume-loud-bold-duotone',    // marketing
            'gavel-bold-duotone'     => 'book-bookmark-bold-duotone',  // legal
            'hammer-bold-duotone'    => 'book-bookmark-bold-duotone',  // legal (defensive)
            'bullhorn-bold-duotone'  => 'volume-loud-bold-duotone',    // marketing (defensive)
        ];

        foreach ($fixes as $bad => $good) {
            DB::table('specializations')->where('icon', $bad)->update(['icon' => $good]);
        }
    }

    public function down(): void
    {
        // Not reversible — old slugs were broken anyway.
    }
};
