<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Merchant;

class MerchantTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_create_merchant()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/merchants', [
                'name' => 'John Doe',
                'email' => 'john@shop.com',
                'business_name' => 'John Shop',
                'account_number' => '1234567890',
                'bank_name' => 'Bank of Ghana'
            ]);

        $response->assertStatus(201)
            ->assertJson(['success' => true])
            ->assertJsonStructure(['success', 'data', 'message']);
    }

    public function test_list_merchants()
    {
        Merchant::factory()->count(2)->create();
        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/merchants');
        $response->assertStatus(200)
            ->assertJsonStructure(['*' => [
                'id',
                'name',
                'email',
                'business_name',
                'account_number',
                'bank_name',
                'is_active',
                'wallet'
            ]]);
    }

    public function test_update_merchant_status()
    {
        $merchant = Merchant::factory()->create();
        $response = $this->actingAs($this->user, 'sanctum')
            ->patchJson("/api/merchants/{$merchant->id}/status", ['is_active' => false]);

        $response->assertStatus(200)->assertJson(['success' => true]);
        $this->assertDatabaseHas('merchants', ['id' => $merchant->id, 'is_active' => false]);
    }
}
