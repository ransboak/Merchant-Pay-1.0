<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Merchant;

class SettlementTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $merchant;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->merchant = Merchant::factory()->create();
        $this->merchant->wallet()->create(['balance'=>1000]);
    }

    public function test_run_settlement()
    {
        $response = $this->actingAs($this->user,'sanctum')->postJson('/api/settlements/run');
        $response->assertStatus(200)->assertJson(['success'=>true]);

        $this->assertDatabaseHas('wallets',['merchant_id'=>$this->merchant->id,'balance'=>0]);
        $this->assertDatabaseHas('settlements',['merchant_id'=>$this->merchant->id,'amount'=>1000]);
    }
}
