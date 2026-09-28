<?php

namespace Database\Factories;

use App\Models\ProgramStudi;
use Illuminate\Database\Eloquent\Factories\Factory;

class MahasiswaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'program_studi_id' => ProgramStudi::inRandomOrder()->value('id') ?? ProgramStudi::factory(),
            'nim' => $this->faker->unique()->numerify('H1A#########'),
            'nama' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'angkatan' => $this->faker->numberBetween(2020, 2026),
            'ipk' => $this->faker->randomFloat(2, 2.5, 4.0),
            'aktif' => $this->faker->boolean(90),
        ];
    }
}
