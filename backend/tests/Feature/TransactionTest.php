<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Merchant;
use App\Models\Wallet;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $merchant;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->merchant = Merchant::factory()->create();
        $this->merchant->wallet()->create(['balance'=>0]);
    }

    public function test_create_transaction()
    {
        $response = $this->actingAs($this->user,'sanctum')
                         ->postJson('/api/transactions', [
                             'merchant_id'=>$this->merchant->id,
                             'amount'=>1000,
                             'payment_reference'=>'TXN001'
                         ]);

        $response->assertStatus(201)->assertJson(['success'=>true]);
        $this->assertDatabaseHas('transactions',['payment_reference'=>'TXN001']);
        $this->assertDatabaseHas('wallets',['merchant_id'=>$this->merchant->id,'balance'=>985]);
    }

    public function test_total_fees()
    {
        $this->actingAs($this->user,'sanctum')
             ->postJson('/api/transactions', [
                 'merchant_id'=>$this->merchant->id,
                 'amount'=>1000,
                 'payment_reference'=>'TXN001'
             ]);

        $response = $this->actingAs($this->user,'sanctum')->getJson('/api/transactions/fees');
        $response->assertStatus(200)->assertJson(['success'=>true,'total_fees'=>15]);
    }
}
