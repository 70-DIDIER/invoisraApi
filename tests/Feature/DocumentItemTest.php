<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Company;
use App\Models\Document;
use App\Models\DocumentItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentItemTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Document $document;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $company = Company::factory()->create(['user_id' => $this->user->id]);
        $client = Client::factory()->create(['user_id' => $this->user->id]);
        $this->document = Document::factory()->create([
            'user_id' => $this->user->id,
            'company_id' => $company->id,
            'client_id' => $client->id,
        ]);
        $this->token = $this->user->createToken('test')->plainTextToken;
    }

    public function test_can_list_items(): void
    {
        DocumentItem::factory(3)->create(['document_id' => $this->document->id]);

        $response = $this->withToken($this->token)
            ->getJson("/api/v1/documents/{$this->document->id}/items");

        $response->assertOk()
            ->assertJsonCount(3);
    }

    public function test_can_create_item(): void
    {
        $response = $this->withToken($this->token)
            ->postJson("/api/v1/documents/{$this->document->id}/items", [
                'designation' => 'Robinets',
                'quantity' => 3,
                'unit_price' => 45.50,
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'designation' => 'Robinets',
                'total_price' => 136.50,
            ]);
    }

    public function test_can_show_item(): void
    {
        $item = DocumentItem::factory()->create(['document_id' => $this->document->id]);

        $response = $this->withToken($this->token)
            ->getJson("/api/v1/documents/{$this->document->id}/items/{$item->id}");

        $response->assertOk()
            ->assertJson(['id' => $item->id]);
    }

    public function test_can_update_item(): void
    {
        $item = DocumentItem::factory()->create([
            'document_id' => $this->document->id,
            'designation' => 'Ancien',
        ]);

        $response = $this->withToken($this->token)
            ->putJson("/api/v1/documents/{$this->document->id}/items/{$item->id}", [
                'designation' => 'Nouveau',
            ]);

        $response->assertOk()
            ->assertJson(['designation' => 'Nouveau']);
    }

    public function test_can_delete_item(): void
    {
        $item = DocumentItem::factory()->create(['document_id' => $this->document->id]);

        $response = $this->withToken($this->token)
            ->deleteJson("/api/v1/documents/{$this->document->id}/items/{$item->id}");

        $response->assertOk();
        $this->assertModelMissing($item);
    }

    public function test_total_price_is_auto_calculated(): void
    {
        $response = $this->withToken($this->token)
            ->postJson("/api/v1/documents/{$this->document->id}/items", [
                'designation' => 'Test',
                'quantity' => 10,
                'unit_price' => 25,
            ]);

        $response->assertStatus(201)
            ->assertJson(['total_price' => 250]);
    }
}
