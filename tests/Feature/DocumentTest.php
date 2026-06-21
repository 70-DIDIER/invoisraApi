<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Company;
use App\Models\Document;
use App\Models\DocumentItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Company $company;
    private Client $client;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->company = Company::factory()->create(['user_id' => $this->user->id]);
        $this->client = Client::factory()->create(['user_id' => $this->user->id]);
        $this->token = $this->user->createToken('test')->plainTextToken;
    }

    public function test_can_list_documents(): void
    {
        Document::factory(3)->create([
            'user_id' => $this->user->id,
            'company_id' => $this->company->id,
            'client_id' => $this->client->id,
        ]);

        $response = $this->withToken($this->token)->getJson('/api/v1/documents');

        $response->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_can_create_document(): void
    {
        $response = $this->withToken($this->token)->postJson('/api/v1/documents', [
            'client_id' => $this->client->id,
            'type' => 'quote',
            'project_name' => 'Rénovation',
            'issue_date' => '2026-06-21',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('project_name', 'Rénovation');
    }

    public function test_cannot_create_document_without_company(): void
    {
        $userWithoutCompany = User::factory()->create();
        $token = $userWithoutCompany->createToken('test')->plainTextToken;

        $response = $this->withToken($token)->postJson('/api/v1/documents', [
            'client_id' => $this->client->id,
            'type' => 'quote',
            'project_name' => 'Test',
            'issue_date' => '2026-06-21',
        ]);

        $response->assertStatus(400);
    }

    public function test_can_show_document(): void
    {
        $document = Document::factory()->create([
            'user_id' => $this->user->id,
            'company_id' => $this->company->id,
            'client_id' => $this->client->id,
        ]);

        $response = $this->withToken($this->token)->getJson("/api/v1/documents/{$document->id}");

        $response->assertOk()
            ->assertJson(['id' => $document->id]);
    }

    public function test_cannot_show_other_user_document(): void
    {
        $otherUser = User::factory()->create();
        $document = Document::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->withToken($this->token)->getJson("/api/v1/documents/{$document->id}");

        $response->assertStatus(403);
    }

    public function test_can_update_document(): void
    {
        $document = Document::factory()->create([
            'user_id' => $this->user->id,
            'company_id' => $this->company->id,
            'client_id' => $this->client->id,
        ]);

        $response = $this->withToken($this->token)->putJson("/api/v1/documents/{$document->id}", [
            'project_name' => 'Mis à jour',
        ]);

        $response->assertOk()
            ->assertJson(['project_name' => 'Mis à jour']);
    }

    public function test_can_delete_document(): void
    {
        $document = Document::factory()->create([
            'user_id' => $this->user->id,
            'company_id' => $this->company->id,
            'client_id' => $this->client->id,
        ]);

        $response = $this->withToken($this->token)->deleteJson("/api/v1/documents/{$document->id}");

        $response->assertOk();
        $this->assertModelMissing($document);
    }

    public function test_can_filter_by_type(): void
    {
        Document::factory()->quote()->create([
            'user_id' => $this->user->id,
            'company_id' => $this->company->id,
            'client_id' => $this->client->id,
        ]);
        Document::factory()->invoice()->create([
            'user_id' => $this->user->id,
            'company_id' => $this->company->id,
            'client_id' => $this->client->id,
        ]);

        $response = $this->withToken($this->token)->getJson('/api/v1/documents?type=quote');

        $response->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_can_search_documents(): void
    {
        Document::factory()->create([
            'user_id' => $this->user->id,
            'company_id' => $this->company->id,
            'client_id' => $this->client->id,
            'project_name' => 'Cuisine',
        ]);
        Document::factory()->create([
            'user_id' => $this->user->id,
            'company_id' => $this->company->id,
            'client_id' => $this->client->id,
            'project_name' => 'Salle de bain',
        ]);

        $response = $this->withToken($this->token)->getJson('/api/v1/documents?search=Cuisine');

        $response->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_can_download_pdf(): void
    {
        $document = Document::factory()->create([
            'user_id' => $this->user->id,
            'company_id' => $this->company->id,
            'client_id' => $this->client->id,
        ]);

        $response = $this->withToken($this->token)->getJson("/api/v1/documents/{$document->id}/download");

        $response->assertOk()
            ->assertJsonStructure(['filename', 'content', 'content_type'])
            ->assertJsonPath('content_type', 'application/pdf');
    }
}
