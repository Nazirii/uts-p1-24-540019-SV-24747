<?php

namespace Database\Factories;

use App\Models\Agenda;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Agenda>
 */
class AgendaFactory extends Factory
{
    protected $model = Agenda::class;

    public function definition(): array
    {
        return [
            'judul'      => fake()->sentence(3),
            'tanggal'    => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'waktu'      => fake()->time('H:i'),
            'lokasi'     => fake()->city(),
            'keterangan' => fake()->sentence(),
        ];
    }
}
