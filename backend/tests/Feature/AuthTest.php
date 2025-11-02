<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_user()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['success','data'=>['user','token'],'message']);
        $this->assertDatabaseHas('users',['email'=>'john@example.com']);
    }

    public function test_login_user()
    {
        $user = User::factory()->create([
            'password'=>bcrypt('secret')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'secret'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['success','data'=>['user','token'],'message']);
    }

    public function test_logout_user()
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization'=>"Bearer $token"
        ])->postJson('/api/logout');

        $response->assertStatus(200)
                 ->assertJson(['success'=>true,'message'=>'Logged out successfully']);
    }
}
