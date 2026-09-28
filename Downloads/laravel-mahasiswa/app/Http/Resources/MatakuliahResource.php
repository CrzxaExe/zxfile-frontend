<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MatakuliahResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'kode' => $this->kode,
            'nama' => $this->nama,
            'sks' => $this->sks,
            'semester' => $this->semester,
            'program_studi' => $this->whenLoaded('programStudi', function () {
                return [
                    'id' => $this->programStudi->id,
                    'kode' => $this->programStudi->kode,
                    'nama' => $this->programStudi->nama,
                ];
            }),
            'dibuat_pada' => $this->created_at->toIso8601String(),
        ];
    }
}
