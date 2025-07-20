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
        Schema::create('absensis', function (Blueprint $table) {
        $table->id();
        $table->foreignId('jadwal_tugas_id')->constrained()->onDelete('cascade');
        $table->foreignId('petugas_id')->constrained()->onDelete('cascade');
        $table->enum('status', ['checkin', 'terlambat checkin', 'lupa checkout', 'tidak masuk']);
        $table->decimal('latitude', 10, 8)->nullable();
        $table->decimal('longitude', 11, 8)->nullable();
        $table->timestamp('checkin_at')->nullable();
        $table->timestamp('checkout_at')->nullable();
        $table->string('foto_checkin')->nullable();
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
