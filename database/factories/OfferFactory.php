<?php

namespace Database\Factories;

use App\Models\Offer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfferFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Offer::class;

    /**
     * Define the model's default state.
     * 
     * @return array
     */
    public function definition()
    {
        return [
            'offer_title' => $this->faker->company(),
            'slug' => $this->faker->company(),
            'offer_duration_in_days' =>  rand(30, 90),
            'offer_start' => Carbon::now()->subMonth(rand(0, 5)),
            'offer_end' => Carbon::now()->subMonth(rand(0, 5))
        ];
    }
}
