<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CompanyTest extends TestCase
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

    public function test_can_create_company(): void
    {
        $response = $this->withToken($this->token)->postJson('/api/v1/company', [
            'name' => 'Ma Société',
            'phone' => '0123456789',
            'address' => '10 rue de Paris',
            'manager_name' => 'Jean Dupont',
        ]);

        $response->assertStatus(201)
            ->assertJson(['name' => 'Ma Société']);

        $this->assertDatabaseHas('companies', ['name' => 'Ma Société']);
    }

    public function test_cannot_create_two_companies(): void
    {
        Company::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withToken($this->token)->postJson('/api/v1/company', [
            'name' => 'Autre Société',
            'phone' => '0123456789',
            'address' => '20 rue de Lyon',
            'manager_name' => 'test',
        ]);

        $response->assertStatus(409);
    }

    public function test_can_show_company(): void
    {
        Company::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withToken($this->token)->getJson('/api/v1/company');

        $response->assertOk()
            ->assertJsonPath('user_id', $this->user->id);
    }

    public function test_can_update_company(): void
    {
        Company::factory()->create(['user_id' => $this->user->id, 'name' => 'Ancien']);

        $response = $this->withToken($this->token)->putJson('/api/v1/company', [
            'name' => 'Nouveau Nom',
        ]);

        $response->assertOk()
            ->assertJson(['name' => 'Nouveau Nom']);
    }

    public function test_can_upload_logo(): void
    {
        Company::factory()->create(['user_id' => $this->user->id]);
        Storage::fake('public');

        $file = UploadedFile::fake()->image('logo.jpg');

        $response = $this->withToken($this->token)
            ->postJson('/api/v1/company/upload', [
                'file' => $file,
                'type' => 'logo',
            ]);

        $response->assertOk()
            ->assertJsonPath('type', 'logo');
    }

    public function test_can_delete_uploaded_file(): void
    {
        Company::factory()->create(['user_id' => $this->user->id, 'logo' => '/storage/companies/1/logo.jpg']);

        $response = $this->withToken($this->token)
            ->deleteJson('/api/v1/company/upload/logo');

        $response->assertOk();
        $this->assertDatabaseHas('companies', ['id' => $this->user->company->id, 'logo' => null]);
    }
}
