<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('investment_opportunities', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('summary');           // short pitch
            $table->longText('description');   // full detailed body

            // Classification
            $table->string('sector', 80);           // العقارات، الصناعة، السياحة...
            $table->string('city', 80)->nullable();
            $table->string('region', 80)->nullable();

            // Financials
            $table->decimal('investment_min', 15, 2);   // بحد أدنى (SAR)
            $table->decimal('investment_max', 15, 2)->nullable();
            $table->decimal('expected_roi', 5, 2)->nullable();  // نسبة العائد المتوقّع %
            $table->integer('payback_months')->nullable();       // فترة الاسترداد بالأشهر
            $table->integer('duration_years')->nullable();       // مدة المشروع بالسنوات
            $table->string('risk_level', 20)->default('medium'); // low | medium | high

            // Presentation
            $table->string('cover_image')->nullable();
            $table->json('gallery')->nullable();    // additional images
            $table->json('highlights')->nullable(); // [{title, value, icon}]
            $table->json('documents')->nullable();  // [{title, path}]

            // Source (internal or government API)
            $table->string('source', 20)->default('internal'); // internal | gov_api
            $table->string('source_name')->nullable();          // e.g. "منشآت"، "المركز الوطني للاستثمار"
            $table->string('source_url')->nullable();
            $table->string('external_ref', 100)->nullable();    // ID from external API

            // Status
            $table->string('status', 20)->default('open');     // open | closed | in_review | draft
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('deadline_at')->nullable();

            // Metrics
            $table->integer('views_count')->default(0);
            $table->integer('applications_count')->default(0);

            $table->timestamps();
            $table->index(['status', 'sector']);
            $table->index('is_featured');
        });

        // Applications (interest requests from B2B partners)
        Schema::create('investment_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opportunity_id')->constrained('investment_opportunities')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->string('reference', 20)->unique();
            $table->string('company_name');
            $table->string('contact_name');
            $table->string('contact_email');
            $table->string('contact_phone', 30);
            $table->decimal('investment_amount', 15, 2)->nullable();
            $table->text('message')->nullable();

            $table->string('status', 20)->default('new'); // new | contacted | qualified | rejected
            $table->text('admin_notes')->nullable();

            $table->timestamps();
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investment_applications');
        Schema::dropIfExists('investment_opportunities');
    }
};
