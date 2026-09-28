<?php

namespace Database\Seeders;

use App\Models\ProgramStudi;
use Illuminate\Database\Seeder;

class ProgramStudiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kode' => 'TK', 'nama' => 'Teknik Komputer'],
            ['kode' => 'TI', 'nama' => 'Teknik Informatika'],
            ['kode' => 'SI', 'nama' => 'Sistem Informasi'],
        ];

        foreach ($data as $item) {
            ProgramStudi::updateOrCreate(['kode' => $item['kode']], $item);
        }
    }
}
