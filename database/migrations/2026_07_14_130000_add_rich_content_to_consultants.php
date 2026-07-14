<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('consultants', function (Blueprint $table) {
            if (! Schema::hasColumn('consultants', 'rich_content')) {
                $table->json('rich_content')->nullable()->after('bio_en');
            }
        });
    }

    public function down(): void
    {
        Schema::table('consultants', function (Blueprint $table) {
            if (Schema::hasColumn('consultants', 'rich_content')) {
                $table->dropColumn('rich_content');
            }
        });
    }
};
