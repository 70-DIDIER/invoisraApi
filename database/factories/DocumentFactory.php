<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Company;
use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Document>
 */
class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition(): array
    {
        $type = fake()->randomElement(['quote', 'invoice']);

        return [
            'user_id' => User::factory(),
            'company_id' => Company::factory(),
            'client_id' => Client::factory(),
            'type' => $type,
            'number' => strtoupper($type === 'invoice' ? 'INV-' : 'QTE-') . fake()->unique()->bothify('####'),
            'project_name' => fake()->sentence(3),
            'issue_date' => fake()->date(),
            'valid_until' => $type === 'quote' ? fake()->dateTimeBetween('+1 week', '+1 month') : null,
            'notes' => fake()->optional()->paragraph(),
            'subtotal' => fake()->randomFloat(2, 100, 5000),
            'labor_cost' => fake()->randomFloat(2, 0, 1000),
            'transport_cost' => fake()->randomFloat(2, 0, 500),
            'other_cost' => fake()->randomFloat(2, 0, 300),
            'total' => fake()->randomFloat(2, 100, 7000),
            'total_in_words' => null,
            'pdf_template' => null,
        ];
    }

    public function quote(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'quote',
            'number' => 'QTE-' . fake()->unique()->bothify('####'),
            'valid_until' => fake()->dateTimeBetween('+1 week', '+1 month'),
        ]);
    }

    public function invoice(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'invoice',
            'number' => 'INV-' . fake()->unique()->bothify('####'),
            'valid_until' => null,
        ]);
    }
}
