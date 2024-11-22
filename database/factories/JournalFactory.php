<?php

namespace Database\Factories;

use App\Journal;
use App\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class JournalFactory extends Factory
{
    /**
     * The name of the model the factory is for.
     *
     * @var string
     */
    protected $model = Journal::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'date' => $this->faker->date(),
            'text' => $this->faker->text(),
            'client_id' => Client::factory(),
        ];
    }
}
