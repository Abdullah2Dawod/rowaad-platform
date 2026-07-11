<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('consultants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Application status
            $table->string('status', 20)->default('draft'); // draft|submitted|approved|rejected
            $table->tinyInteger('completed_step')->default(0); // 0..3
            $table->text('rejection_reason')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();

            // STEP 1 — Personal
            $table->string('full_name_ar')->nullable();
            $table->string('full_name_en')->nullable();
            $table->string('national_id', 20)->nullable();
            $table->string('nationality')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('gender', 10)->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('SA');
            $table->string('professional_title')->nullable();
            $table->text('bio_ar')->nullable();
            $table->text('bio_en')->nullable();

            // STEP 2 — Documents
            $table->string('avatar_path')->nullable();
            $table->string('cv_path')->nullable();
            $table->string('national_id_path')->nullable();
            $table->json('certificates')->nullable();

            // STEP 3 — Services & pricing
            $table->foreignId('specialization_id')->nullable()->constrained()->nullOnDelete();
            $table->json('secondary_specializations')->nullable();
            $table->json('services')->nullable();
            $table->integer('years_experience')->default(0);
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->integer('session_duration_min')->default(60);
            $table->json('languages')->nullable();
            $table->json('availability')->nullable();

            // Public profile flags
            $table->boolean('is_featured')->default(false);
            $table->integer('rating_count')->default(0);
            $table->decimal('rating_avg', 3, 2)->default(0);
            $table->integer('sessions_completed')->default(0);

            $table->timestamps();
            $table->index(['status', 'specialization_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultants');
    }
};
