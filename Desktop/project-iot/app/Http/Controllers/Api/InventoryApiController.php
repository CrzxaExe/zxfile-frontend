<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductionLog;
use App\Models\Rak;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class InventoryApiController extends Controller
{
    public function receiveEsp32Data(Request $request)
    {
        $validated = $request->validate([
            'total_count' => 'required|integer',
            'speed_rpm'   => 'required|numeric',
        ]);

        $rakIndex = ($validated['total_count'] % 3);
        $kodeRak = ($rakIndex == 1) ? 'RAK-A' : (($rakIndex == 2) ? 'RAK-B' : 'RAK-C');
        $rak = Rak::firstOrCreate(['kode_rak' => $kodeRak], ['nama_rak' => 'Rak Storage ' . $kodeRak]);

        $product = Product::firstOrCreate(
            ['sku' => 'FA-DEFAULT-01'],
            [
                'nama_pakaian' => 'Kemeja Fashion Standard',
                'kategori'     => 'Atasan',
                'stok_tersedia'=> 0,
                'total_produksi'=> 0,
                'total_terjual' => 0
            ]
        );

        $product->increment('total_produksi');
        $product->increment('stok_tersedia');

        if (!$product->qr_code_path) {
            $qrPath = 'qrcodes/' . $product->sku . '.png';
            $qrImage = QrCode::format('png')->size(200)->generate($product->sku);
            Storage::disk('public')->put($qrPath, $qrImage);
            $product->update(['qr_code_path' => $qrPath]);
        }

        ProductionLog::create([
            'product_id'   => $product->id,
            'rak_id'       => $rak->id,
            'kecepatan_rpm'=> $validated['speed_rpm'],
            'jumlah_masuk' => 1
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data berhasil dicatat'
        ], 200);
    }
}