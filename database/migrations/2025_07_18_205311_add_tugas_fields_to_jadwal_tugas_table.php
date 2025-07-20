<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
    {
        Schema::table('jadwal_tugas', function (Blueprint $table) {
            $table->text('deskripsi_tugas')->nullable()->after('waktu_selesai');
        });
    }

    public function down(): void
    {
        Schema::table('jadwal_tugas', function (Blueprint $table) {
            $table->dropColumn('deskripsi_tugas');
        });
    }
};
