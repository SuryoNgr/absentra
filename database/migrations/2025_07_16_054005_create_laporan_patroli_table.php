<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laporan_patroli', function (Blueprint $table) {
        $table->id();
        $table->foreignId('jadwal_tugas_id')->constrained()->onDelete('cascade');
        $table->foreignId('petugas_id')->constrained()->onDelete('cascade');
        $table->text('catatan')->nullable();
        $table->decimal('latitude', 10, 7)->nullable();
        $table->decimal('longitude', 10, 7)->nullable();
        $table->string('foto')->nullable();
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_patroli');
    }
};
