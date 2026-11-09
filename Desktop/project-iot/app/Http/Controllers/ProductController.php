<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Rak;
use App\Models\ProductionLog;

class ProductController extends Controller
{
    /**
     * Ingest telemetry data dari ESP32 / Konveyor
     */
    public function updateInventory(Request $request)
    {
        $totalCount = (int) $request->input('total_count', 1);
        $speedRpm   = (float) $request->input('speed_rpm', 0);
        $rakCode    = $request->input('rak_code', 'RAK-A');

        // Otomatis buat/ambil data rak agar tidak melanggar constraint NOT NULL
        $rak = Rak::firstOrCreate(
            ['kode_rak' => $rakCode],
            ['nama_rak' => 'Rak Storage ' . str_replace('RAK-', '', $rakCode)]
        );

        $product = Product::first();
        if (!$product) {
            $sku = 'SKU-' . strtoupper(substr(md5(microtime()), 0, 6));
            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . $sku;

            // Memenuhi semua kolom NOT NULL pada tabel products
            $product = Product::create([
                'nama_pakaian'   => 'Smart Fashion Outfit',
                'kategori'       => 'Fashion / Inventory',
                'sku'            => $sku,
                'total_produksi' => $totalCount,
                'stok_tersedia'  => $totalCount,
                'total_terjual'  => 0,
                'qr_code_path'   => $qrUrl
            ]);
        } else {
            $product->increment('total_produksi');
            $product->increment('stok_tersedia');
        }

        // Catat log produksi real-time
        ProductionLog::create([
            'product_id'    => $product->id,
            'rak_id'        => $rak->id,
            'kecepatan_rpm' => $speedRpm
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data berhasil dicatat & QR Code siap!'
        ], 200);
    }

    /**
     * Catat Penjualan Barang (Mengurangi stok_tersedia & menambah total_terjual)
     */
    public function sellProduct(Request $request)
    {
        $quantity = (int) $request->input('quantity', 1);

        $product = Product::first();

        if (!$product) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Produk belum ada di database!'
            ], 404);
        }

        // Validasi kecukupan stok
        if ($product->stok_tersedia < $quantity) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Stok tidak mencukupi! Stok saat ini: ' . $product->stok_tersedia
            ], 400);
        }

        // Update stok & terjual
        $product->decrement('stok_tersedia', $quantity);
        $product->increment('total_terjual', $quantity);

        return response()->json([
            'status'        => 'success',
            'message'       => $quantity . ' barang berhasil terjual!',
            'stok_tersedia' => $product->stok_tersedia,
            'total_terjual' => $product->total_terjual
        ], 200);
    }
}