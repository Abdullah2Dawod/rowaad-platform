<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('feasibility_studies', function (Blueprint $table) {
            if (! Schema::hasColumn('feasibility_studies', 'extra_files')) {
                $table->json('extra_files')->nullable()->after('file_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('feasibility_studies', function (Blueprint $table) {
            if (Schema::hasColumn('feasibility_studies', 'extra_files')) {
                $table->dropColumn('extra_files');
            }
        });
    }
};
