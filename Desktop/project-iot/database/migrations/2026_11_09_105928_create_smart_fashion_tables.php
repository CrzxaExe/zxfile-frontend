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
        // 1. Tabel Master Rak
        Schema::create('raks', function (Blueprint $table) {
            $table->id();
            $table->string('kode_rak')->unique(); // Contoh: RAK-A, RAK-B, RAK-C
            $table->string('nama_rak');
            $table->integer('kapasitas_maksimal')->default(100);
            $table->timestamps();
        });

        // 2. Tabel Master Pakaian / Produk
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique(); // Kode Barang
            $table->string('nama_pakaian');
            $table->string('kategori');
            $table->integer('stok_tersedia')->default(0);
            $table->integer('total_produksi')->default(0);
            $table->integer('total_terjual')->default(0);
            $table->string('qr_code_path')->nullable();
            $table->timestamps();
        });

        // 3. Tabel Riwayat Produksi dari ESP32
        Schema::create('production_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('cascade');
            $table->foreignId('rak_id')->nullable()->constrained('raks')->onDelete('set null');
            $table->float('kecepatan_rpm')->default(0);
            $table->integer('jumlah_masuk')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_logs');
        Schema::dropIfExists('products');
        Schema::dropIfExists('raks');
    }
};