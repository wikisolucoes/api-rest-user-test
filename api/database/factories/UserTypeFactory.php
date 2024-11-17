<?php

namespace Database\Factories;

use App\Models\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserTypeFactory extends Factory
{
    protected $model = UserType::class;

    public function definition()
    {
        return [
            'code' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{2}'),
            'name' => $this->faker->word,
            'description' => $this->faker->optional()->sentence,
            'status' => $this->faker->randomElement(['A', 'I']),
        ];
    }
}
