<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('feasibility_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->string('reference', 20)->unique();

            // Project identity
            $table->string('project_title');
            $table->string('sector', 100);
            $table->string('sub_sector', 100)->nullable();
            $table->string('city', 80)->nullable();
            $table->string('country', 80)->default('SA');

            // Scope
            $table->text('idea_description');
            $table->json('goals')->nullable();
            $table->json('study_types')->nullable();

            // Financials
            $table->decimal('estimated_budget', 15, 2)->nullable();
            $table->string('funding_source', 60)->nullable();

            // Timeline
            $table->string('urgency', 20)->default('normal');
            $table->date('needed_by')->nullable();

            // Client info
            $table->string('company_name')->nullable();
            $table->string('company_size', 40)->nullable();
            $table->string('contact_name');
            $table->string('contact_email');
            $table->string('contact_phone', 30);

            // Files
            $table->json('attachments')->nullable();

            // Workflow
            $table->string('status', 24)->default('new');
            $table->decimal('quoted_price', 15, 2)->nullable();
            $table->text('admin_notes')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feasibility_requests');
    }
};
