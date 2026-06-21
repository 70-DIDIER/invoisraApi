<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Company;
use App\Models\Document;
use App\Models\DocumentItem;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $company = Company::factory()->create([
            'user_id' => $user->id,
        ]);

        $clients = Client::factory(10)->create([
            'user_id' => $user->id,
        ]);

        $clients->each(function (Client $client) use ($user, $company) {
            Document::factory(3)
                ->quote()
                ->create([
                    'user_id' => $user->id,
                    'company_id' => $company->id,
                    'client_id' => $client->id,
                ])
                ->each(function (Document $document) {
                    DocumentItem::factory(rand(1, 5))->create([
                        'document_id' => $document->id,
                    ]);
                });

            Document::factory(2)
                ->invoice()
                ->create([
                    'user_id' => $user->id,
                    'company_id' => $company->id,
                    'client_id' => $client->id,
                ])
                ->each(function (Document $document) {
                    DocumentItem::factory(rand(1, 5))->create([
                        'document_id' => $document->id,
                    ]);
                });
        });
    }
}
