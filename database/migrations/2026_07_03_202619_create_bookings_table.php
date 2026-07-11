<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('consultant_id')->constrained()->cascadeOnDelete();

            // Booking details
            $table->string('reference', 20)->unique(); // e.g. "BK-A7X9K2"
            $table->date('preferred_date');
            $table->time('preferred_time');
            $table->integer('duration_min')->default(60);
            $table->string('service_title')->nullable();
            $table->text('notes')->nullable();

            // Pricing snapshot (frozen at booking time)
            $table->decimal('amount',           10, 2); // total the user pays
            $table->decimal('consultant_share', 10, 2); // 50%
            $table->decimal('platform_share',   10, 2); // 50%
            $table->decimal('zakat_amount',     10, 2); // 15% of consultant share

            // Status flow:
            // pending_payment → paid → confirmed (admin approved)
            //                              → cancelled | completed
            $table->string('status', 24)->default('pending_payment');
            $table->text('cancellation_reason')->nullable();

            // Payment (mocked for now)
            $table->string('payment_method', 20)->nullable(); // mock|mada|visa|apple_pay
            $table->string('payment_ref', 60)->nullable();
            $table->timestamp('paid_at')->nullable();

            // Session details (set on confirmation)
            $table->string('meeting_url')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
            $table->index(['consultant_id', 'status']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
