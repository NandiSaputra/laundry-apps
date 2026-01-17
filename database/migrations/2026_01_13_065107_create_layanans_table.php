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
        Schema::create('layanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori_layanans')->onDelete('cascade');
            $table->string('kode_layanan')->unique();
            $table->string('nama');
            $table->enum('satuan', ['kg', 'pcs', 'pasang', 'meter', 'm2']);
            $table->decimal('harga', 12, 2);
            $table->integer('estimasi_jam')->default(24);
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('layanans');
    }
};
