<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DhcpEntry>
 */
class DhcpEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'mac_address' => $this->faker->macAddress,
            'ip_address' => '',
            'hostname' => '',
            'added_by' => $this->faker->name,
            'owner' => $this->faker->name,
        ];
    }
}
