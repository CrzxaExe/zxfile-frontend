<?php

namespace Database\Factories;

use App\Models\ProgramStudi;
use Illuminate\Database\Eloquent\Factories\Factory;

class MatakuliahFactory extends Factory
{
    public function definition(): array
    {
        return [
            'program_studi_id' => ProgramStudi::inRandomOrder()->value('id') ?? ProgramStudi::factory(),
            'kode' => strtoupper($this->faker->unique()->bothify('??###')),
            'nama' => ucfirst($this->faker->words(3, true)),
            'sks' => $this->faker->numberBetween(2, 4),
            'semester' => $this->faker->numberBetween(1, 8),
        ];
    }
}
