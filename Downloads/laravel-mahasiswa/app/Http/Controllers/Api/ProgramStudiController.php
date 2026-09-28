<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MahasiswaResource;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class ProgramStudiController extends Controller
{
    /**
     * Tugas E.2: GET /api/program-studi/{id}/mahasiswa
     * Daftar mahasiswa pada satu program studi, dengan pagination.
     */
    public function mahasiswa(Request $request, ProgramStudi $programStudi)
    {
        $perHalaman = min($request->integer('per_halaman', 10), 100);

        $kueri = $programStudi->mahasiswas()->with('programStudi');

        $urutan = $request->query('urut', 'nama');
        $arah = $request->query('arah', 'asc');
        $kolomDiizinkan = ['nama', 'nim', 'angkatan', 'ipk'];

        if (in_array($urutan, $kolomDiizinkan, true)) {
            $kueri->orderBy($urutan, $arah === 'desc' ? 'desc' : 'asc');
        }

        return MahasiswaResource::collection($kueri->paginate($perHalaman));
    }
}
