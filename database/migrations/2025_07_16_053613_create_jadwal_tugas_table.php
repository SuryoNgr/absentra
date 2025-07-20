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
       Schema::create('jadwal_tugas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('petugas_id')->constrained()->onDelete('cascade');
        $table->foreignId('work_location_id')->constrained()->onDelete('cascade');
        $table->string('nama_tim');
        $table->dateTime('waktu_mulai');
        $table->dateTime('waktu_selesai');
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_tugas');
    }
};
