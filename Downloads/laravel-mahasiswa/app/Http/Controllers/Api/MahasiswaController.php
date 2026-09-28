<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMahasiswaRequest;
use App\Http\Requests\UpdateMahasiswaRequest;
use App\Http\Resources\MahasiswaResource;
use App\Models\Mahasiswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    /**
     * Kolom yang boleh dipilih lewat parameter ?fields=
     * Whitelist ini wajib ada agar klien tidak bisa meminta kolom sensitif
     * atau kolom yang tidak ada (lihat pembahasan F.3).
     */
    private const KOLOM_FIELDS_DIIZINKAN = [
        'id', 'nim', 'nama', 'email', 'angkatan', 'ipk', 'aktif',
        'program_studi', 'dibuat_pada',
    ];

    /**
     * Kolom yang boleh dipakai untuk pengurutan (?urut=).
     * Whitelist mencegah SQL injection / error lewat nama kolom bebas.
     */
    private const KOLOM_URUT_DIIZINKAN = ['nama', 'nim', 'angkatan', 'ipk'];

    public function index(Request $request)
    {
        $kueri = Mahasiswa::query()->with('programStudi');

        if ($request->filled('cari')) {
            $kataKunci = $request->query('cari');
            $kueri->where(function ($sub) use ($kataKunci) {
                $sub->where('nama', 'like', '%' . $kataKunci . '%')
                    ->orWhere('nim', 'like', '%' . $kataKunci . '%');
            });
        }

        if ($request->filled('angkatan')) {
            $kueri->where('angkatan', $request->integer('angkatan'));
        }

        if ($request->filled('program_studi_id')) {
            $kueri->where('program_studi_id', $request->integer('program_studi_id'));
        }

        $urutan = $request->query('urut', 'nama');
        $arah = $request->query('arah', 'asc');

        if (in_array($urutan, self::KOLOM_URUT_DIIZINKAN, true)) {
            $kueri->orderBy($urutan, $arah === 'desc' ? 'desc' : 'asc');
        }

        $perHalaman = min($request->integer('per_halaman', 10), 100);
        $halaman = $kueri->paginate($perHalaman)->appends($request->query());

        $respons = MahasiswaResource::collection($halaman)->response()->getData(true);

        // Tugas E.3: parameter fields untuk memilih kolom yang ditampilkan.
        if ($request->filled('fields')) {
            $kolomDiminta = array_filter(array_map('trim', explode(',', $request->query('fields'))));
            $kolomValid = array_values(array_intersect(self::KOLOM_FIELDS_DIIZINKAN, $kolomDiminta));

            if (! empty($kolomValid)) {
                $respons['data'] = array_map(function ($item) use ($kolomValid) {
                    return array_intersect_key($item, array_flip($kolomValid));
                }, $respons['data']);
            }
        }

        return response()->json($respons);
    }

    public function store(StoreMahasiswaRequest $request): JsonResponse
    {
        $mahasiswa = Mahasiswa::create($request->validated());
        $mahasiswa->load('programStudi');

        return response()->json([
            'sukses' => true,
            'pesan' => 'Data mahasiswa berhasil dibuat',
            'data' => new MahasiswaResource($mahasiswa),
        ], 201);
    }

    public function show(Mahasiswa $mahasiswa): JsonResponse
    {
        $mahasiswa->load('programStudi');

        return response()->json([
            'sukses' => true,
            'data' => new MahasiswaResource($mahasiswa),
        ]);
    }

    public function update(UpdateMahasiswaRequest $request, Mahasiswa $mahasiswa): JsonResponse
    {
        $mahasiswa->update($request->validated());
        $mahasiswa->load('programStudi');

        return response()->json([
            'sukses' => true,
            'pesan' => 'Data mahasiswa berhasil diperbarui',
            'data' => new MahasiswaResource($mahasiswa),
        ]);
    }

    public function destroy(Mahasiswa $mahasiswa): JsonResponse
    {
        $mahasiswa->delete();

        return response()->json([
            'sukses' => true,
            'pesan' => 'Data mahasiswa berhasil dihapus',
        ]);
    }
}
