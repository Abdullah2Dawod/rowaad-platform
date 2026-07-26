<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Fix stub consultant_reviews table (originally created empty) — add the real
 * columns the model expects, then reset the fake rating aggregates that were
 * seeded on consultants so counts reflect actual DB rows.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('consultant_reviews', function (Blueprint $table) {
            if (! Schema::hasColumn('consultant_reviews', 'consultant_id')) {
                $table->foreignId('consultant_id')->after('id')->constrained()->cascadeOnDelete();
            }
            if (! Schema::hasColumn('consultant_reviews', 'user_id')) {
                $table->foreignId('user_id')->after('consultant_id')->constrained()->cascadeOnDelete();
            }
            if (! Schema::hasColumn('consultant_reviews', 'rating')) {
                $table->unsignedTinyInteger('rating')->after('user_id');
            }
            if (! Schema::hasColumn('consultant_reviews', 'comment')) {
                $table->text('comment')->nullable()->after('rating');
            }
            if (! Schema::hasColumn('consultant_reviews', 'is_visible')) {
                $table->boolean('is_visible')->default(true)->after('comment');
            }
        });

        // Add unique constraint (consultant + user) if missing — one review per user per consultant
        try {
            Schema::table('consultant_reviews', function (Blueprint $table) {
                $table->unique(['consultant_id', 'user_id'], 'consultant_reviews_unique_per_user');
                $table->index(['consultant_id', 'is_visible']);
            });
        } catch (\Throwable $e) { /* already exists */ }

        // Reset fake rating aggregates on consultants — recompute from actual reviews
        if (Schema::hasColumn('consultants', 'rating_avg')) {
            DB::statement('UPDATE consultants SET rating_avg = 0, rating_count = 0');
            // Backfill from any existing reviews
            $rows = DB::table('consultant_reviews')
                ->selectRaw('consultant_id, AVG(rating) AS avg_rating, COUNT(*) AS total')
                ->where('is_visible', true)
                ->groupBy('consultant_id')
                ->get();
            foreach ($rows as $r) {
                DB::table('consultants')->where('id', $r->consultant_id)->update([
                    'rating_avg'   => round((float) $r->avg_rating, 2),
                    'rating_count' => (int) $r->total,
                ]);
            }
        }
    }

    public function down(): void
    {
        // Non-reversible — we don't drop columns to avoid data loss.
    }
};
