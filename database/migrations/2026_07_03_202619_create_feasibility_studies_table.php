<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('feasibility_studies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // uploader (null = platform)
            $table->foreignId('specialization_id')->nullable()->constrained()->nullOnDelete();

            $table->string('title');
            $table->string('slug')->unique();
            $table->string('cover_image')->nullable();
            $table->text('excerpt');
            $table->longText('description');
            $table->string('sector')->nullable();   // العقارات، الصناعة، ...
            $table->integer('pages_count')->nullable();
            $table->string('language', 5)->default('ar');

            $table->decimal('price', 10, 2)->default(0);
            $table->boolean('is_free')->default(false);
            $table->string('file_path')->nullable(); // main PDF

            // Moderation
            $table->string('status', 20)->default('pending'); // pending|approved|rejected|hidden
            $table->text('rejection_reason')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();

            // Public
            $table->boolean('is_featured')->default(false);
            $table->integer('views_count')->default(0);
            $table->integer('purchases_count')->default(0);

            $table->timestamps();
            $table->index(['status', 'specialization_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feasibility_studies');
    }
};
