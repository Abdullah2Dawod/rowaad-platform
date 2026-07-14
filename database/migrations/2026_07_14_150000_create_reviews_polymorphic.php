<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->morphs('reviewable'); // reviewable_type + reviewable_id
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rating'); // 1..5
            $table->text('comment')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->timestamps();

            // A user can rate a given item only once
            $table->unique(['reviewable_type', 'reviewable_id', 'user_id'], 'reviews_unique_per_user');
            $table->index(['reviewable_type', 'reviewable_id', 'is_visible']);
        });

        // Denormalized aggregates on target tables (safe: skip if already exists)
        foreach ([
            'services'                 => 'services',
            'feasibility_studies'      => 'feasibility_studies',
            'investment_opportunities' => 'investment_opportunities',
        ] as $tbl) {
            if (Schema::hasTable($tbl)) {
                Schema::table($tbl, function (Blueprint $t) {
                    if (! Schema::hasColumn($t->getTable(), 'rating_avg')) {
                        $t->decimal('rating_avg', 3, 2)->default(0);
                    }
                    if (! Schema::hasColumn($t->getTable(), 'rating_count')) {
                        $t->unsignedInteger('rating_count')->default(0);
                    }
                });
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
        foreach (['services', 'feasibility_studies', 'investment_opportunities'] as $tbl) {
            if (Schema::hasTable($tbl)) {
                Schema::table($tbl, function (Blueprint $t) {
                    if (Schema::hasColumn($t->getTable(), 'rating_avg'))   $t->dropColumn('rating_avg');
                    if (Schema::hasColumn($t->getTable(), 'rating_count')) $t->dropColumn('rating_count');
                });
            }
        }
    }
};
