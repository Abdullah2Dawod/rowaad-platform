<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Legacy status values collapse into the three canonical ones.
        DB::table('investment_opportunities')->where('status', 'open')->update(['status' => 'published']);
        DB::table('investment_opportunities')->where('status', 'in_review')->update(['status' => 'draft']);
        DB::table('investment_opportunities')->whereNull('status')->update(['status' => 'draft']);
    }

    public function down(): void
    {
        // No safe reverse — legacy values are ambiguous.
    }
};
