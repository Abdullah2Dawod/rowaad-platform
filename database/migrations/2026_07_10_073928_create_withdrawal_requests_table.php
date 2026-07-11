<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('withdrawal_requests', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('consultant_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);

            // Bank details snapshot at request time
            $table->string('bank_name');
            $table->string('bank_account_holder');
            $table->string('iban');
            $table->string('swift_code')->nullable();

            $table->string('status')->default('pending')->index();
            // pending | approved | paid | rejected

            $table->text('consultant_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->string('payment_reference')->nullable();

            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawal_requests');
    }
};
