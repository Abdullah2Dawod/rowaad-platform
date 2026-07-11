<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('feasibility_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('feasibility_study_id')->constrained()->cascadeOnDelete();

            $table->string('reference', 20)->unique();
            $table->decimal('amount', 10, 2);
            $table->string('status', 20)->default('pending_payment'); // pending_payment|paid|refunded
            $table->string('payment_method', 20)->nullable();
            $table->string('payment_ref', 60)->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
            $table->unique(['user_id', 'feasibility_study_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feasibility_purchases');
    }
};
