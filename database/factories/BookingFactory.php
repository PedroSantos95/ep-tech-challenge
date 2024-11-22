<?php

namespace Database\Factories;

use App\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class BookingFactory extends Factory
{
    /**
     * The name of the model the factory is for.
     *
     * @var string
     */
    protected $model = Booking::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $start = Carbon::make($this->faker->dateTimeBetween('-1 year', '+1 year'));
        $end = $start->copy()->addMinutes($this->faker->randomElement([15, 30, 45, 60, 75, 90]));

        return [
            'start' => $start,
            'end' => $end,
            'notes' => $this->faker->boolean(30) ? $this->faker->paragraphs(1, true) : '',
        ];
    }
}
