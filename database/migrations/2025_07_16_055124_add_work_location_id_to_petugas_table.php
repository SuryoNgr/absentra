<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('petugas', function (Blueprint $table) {
            $table->foreignId('work_location_id')
                  ->nullable()
                  ->after('client_id')
                  ->constrained('work_locations')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('petugas', function (Blueprint $table) {
            $table->dropForeign(['work_location_id']);
            $table->dropColumn('work_location_id');
        });
    }
};
