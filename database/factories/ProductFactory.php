<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => rand(1,10),
            'organization_id' => rand(1,10),
            'name' => $this->faker->name(),
            'short_name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'url' => $this->faker->url(),
            'email_cc' => $this->faker->email(),
            'image' => $this->faker->image()
        ];
    }
}
