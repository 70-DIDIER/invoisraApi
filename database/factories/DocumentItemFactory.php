<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\DocumentItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DocumentItem>
 */
class DocumentItemFactory extends Factory
{
    protected $model = DocumentItem::class;

    public function definition(): array
    {
        $quantity = fake()->randomFloat(2, 1, 50);
        $unitPrice = fake()->randomFloat(2, 5, 500);

        return [
            'document_id' => Document::factory(),
            'designation' => fake()->word(),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_price' => $quantity * $unitPrice,
        ];
    }
}
