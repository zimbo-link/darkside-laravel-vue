<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name,
            'email' => fake()->email,
            'phone' => $this->phone(),
            'address' => fake()->address
        ];
    }

    function phone(){
        return rand(100, 999) . "-" . rand(100, 999) . "-" . rand(1000, 9999);
    }
}
