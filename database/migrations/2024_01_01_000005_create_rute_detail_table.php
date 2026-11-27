<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rute_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rute_id')->constrained('rute')->onDelete('cascade');
            $table->foreignId('lokasi_mangkal_id')->constrained('lokasi_mangkal')->onDelete('cascade');
            $table->integer('urutan');
            $table->time('jam_rencana_mulai')->nullable();
            $table->time('jam_rencana_selesai')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('rute_detail'); }
};
