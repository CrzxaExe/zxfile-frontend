<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMatakuliahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('matakuliah')->id;

        return [
            'program_studi_id' => ['sometimes', 'integer', 'exists:program_studis,id'],
            'kode' => ['sometimes', 'string', 'max:20', 'unique:matakuliahs,kode,' . $id],
            'nama' => ['sometimes', 'string', 'max:100'],
            'sks' => ['sometimes', 'integer', 'min:1', 'max:6'],
            'semester' => ['sometimes', 'integer', 'min:1', 'max:8'],
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
