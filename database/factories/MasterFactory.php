<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Master>
 */
class MasterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'master_name' => $this->faker->word(),
            'sec_name' => $this->faker->word(),
            'master_fam' => $this->faker->word(),
            'master_phone_number' => $this->faker->phoneNumber(),
            // 'spec' => $this->faker->text(20),
            'data_priema' => $this->faker->dateTime(),
        ];
    }

    /**
     * Указать, что мастер уволен.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function dismissed()
    {
        return $this->state(function (array $attributes) {
            return [
                'data_uvoln' => $this->faker->dateTime(),
            ];
        });
    }
}
