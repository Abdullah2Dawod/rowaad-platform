<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('feasibility_studies', function (Blueprint $table) {
            $table->json('rich_content')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('feasibility_studies', function (Blueprint $table) {
            $table->dropColumn('rich_content');
        });
    }
};
