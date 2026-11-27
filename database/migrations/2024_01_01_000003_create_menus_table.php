<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('nama_menu');
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 10, 2);
            $table->string('kategori')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('menus'); }
};
