<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->company(),
            'logo' => null,
            'phone' => fake()->phoneNumber(),
            'email' => fake()->companyEmail(),
            'address' => fake()->address(),
            'manager_name' => fake()->name(),
            'siret' => fake()->numerify('##############'),
            'vat_number' => 'FR' . fake()->numerify('###########'),
            'signature' => null,
            'stamp' => null,
        ];
    }
}
