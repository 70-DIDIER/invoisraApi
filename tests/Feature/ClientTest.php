<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test')->plainTextToken;
    }

    public function test_can_list_clients(): void
    {
        Client::factory(3)->create(['user_id' => $this->user->id]);

        $response = $this->withToken($this->token)->getJson('/api/v1/clients');

        $response->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_can_create_client(): void
    {
        $response = $this->withToken($this->token)->postJson('/api/v1/clients', [
            'name' => 'Client Test',
        ]);

        $response->assertStatus(201)
            ->assertJson(['name' => 'Client Test']);
    }

    public function test_can_show_client(): void
    {
        $client = Client::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withToken($this->token)->getJson("/api/v1/clients/{$client->id}");

        $response->assertOk()
            ->assertJson(['id' => $client->id]);
    }

    public function test_cannot_show_other_user_client(): void
    {
        $otherUser = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->withToken($this->token)->getJson("/api/v1/clients/{$client->id}");

        $response->assertStatus(403);
    }

    public function test_can_update_client(): void
    {
        $client = Client::factory()->create(['user_id' => $this->user->id, 'name' => 'Ancien']);

        $response = $this->withToken($this->token)->putJson("/api/v1/clients/{$client->id}", [
            'name' => 'Nouveau',
        ]);

        $response->assertOk()
            ->assertJson(['name' => 'Nouveau']);
    }

    public function test_can_delete_client(): void
    {
        $client = Client::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withToken($this->token)->deleteJson("/api/v1/clients/{$client->id}");

        $response->assertOk();
        $this->assertModelMissing($client);
    }

    public function test_can_search_clients(): void
    {
        Client::factory()->create(['user_id' => $this->user->id, 'name' => 'Jean Pierre']);
        Client::factory()->create(['user_id' => $this->user->id, 'name' => 'Paul']);

        $response = $this->withToken($this->token)->getJson('/api/v1/clients?search=Jean');

        $response->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_pagination_works(): void
    {
        Client::factory(20)->create(['user_id' => $this->user->id]);

        $response = $this->withToken($this->token)->getJson('/api/v1/clients?per_page=5');

        $response->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure(['data', 'links'])
            ->assertJson([
                'total' => 20,
                'per_page' => 5,
                'current_page' => 1,
                'last_page' => 4,
            ]);
    }
}
