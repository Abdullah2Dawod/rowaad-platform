<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // Billing / contact snapshot (denormalized for auditability)
            $table->string('contact_name');
            $table->string('contact_email');
            $table->string('contact_phone')->nullable();
            $table->string('company_name')->nullable();
            $table->string('tax_id')->nullable();
            $table->text('billing_address')->nullable();

            // Money
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('vat_amount', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->string('currency', 3)->default('SAR');

            // Payment
            $table->string('payment_method')->nullable();      // 'mada', 'apple_pay', 'stc_pay', 'bank_transfer'
            $table->string('payment_reference')->nullable();   // gateway transaction id (never card data)
            $table->string('status')->default('pending')->index(); // pending, paid, failed, cancelled, refunded
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->text('notes')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->morphs('purchasable');                     // polymorphic → FeasibilityStudy, future services, etc
            $table->string('title');                           // snapshot at purchase time
            $table->decimal('unit_price', 12, 2);
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('subtotal', 12, 2);
            $table->json('meta')->nullable();                  // download_count, download_token, etc
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
