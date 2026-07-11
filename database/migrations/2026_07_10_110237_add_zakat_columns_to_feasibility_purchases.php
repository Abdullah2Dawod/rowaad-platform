<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('feasibility_purchases', function (Blueprint $table) {
            $table->decimal('base_amount', 12, 2)->nullable()->after('amount');
            $table->decimal('zakat_amount', 12, 2)->default(0)->after('base_amount');
            $table->decimal('platform_share', 12, 2)->default(0)->after('zakat_amount');
        });
    }

    public function down(): void
    {
        Schema::table('feasibility_purchases', function (Blueprint $table) {
            $table->dropColumn(['base_amount', 'zakat_amount', 'platform_share']);
        });
    }
};
