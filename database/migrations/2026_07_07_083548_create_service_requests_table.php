<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->string('reference', 20)->unique();
            $table->string('service_slug', 100);
            $table->string('service_title', 150);

            $table->string('company_name');
            $table->string('contact_name');
            $table->string('contact_email');
            $table->string('contact_phone', 30);
            $table->string('company_size', 40)->nullable();
            $table->decimal('budget', 15, 2)->nullable();
            $table->string('timeline', 40)->nullable();
            $table->text('project_brief')->nullable();

            $table->string('status', 20)->default('new');
            $table->text('admin_notes')->nullable();

            $table->timestamps();
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
