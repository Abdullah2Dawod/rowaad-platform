<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('investment_opportunities', function (Blueprint $table) {
            if (! Schema::hasColumn('investment_opportunities', 'rich_content')) {
                $table->json('rich_content')->nullable()->after('documents');
            }
        });
    }

    public function down(): void
    {
        Schema::table('investment_opportunities', function (Blueprint $table) {
            if (Schema::hasColumn('investment_opportunities', 'rich_content')) {
                $table->dropColumn('rich_content');
            }
        });
    }
};
