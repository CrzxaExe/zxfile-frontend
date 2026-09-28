<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMatakuliahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'program_studi_id' => ['required', 'integer', 'exists:program_studis,id'],
            'kode' => ['required', 'string', 'max:20', 'unique:matakuliahs,kode'],
            'nama' => ['required', 'string', 'max:100'],
            'sks' => ['required', 'integer', 'min:1', 'max:6'],
            'semester' => ['required', 'integer', 'min:1', 'max:8'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode.unique' => 'Kode matakuliah tersebut sudah terdaftar',
            'sks.max' => 'Jumlah SKS tidak wajar',
        ];
    }
}
