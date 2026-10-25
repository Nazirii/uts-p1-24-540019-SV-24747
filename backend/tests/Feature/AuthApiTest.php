<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_berhasil_mengembalikan_token(): void
    {
        User::factory()->create([
            'email'    => 'tes@agenda.test',
            'password' => 'rahasia123',
        ]);

        $this->postJson('/api/login', [
            'email'    => 'tes@agenda.test',
            'password' => 'rahasia123',
        ])
            ->assertStatus(200)
            ->assertJsonStructure(['message', 'token', 'token_type', 'user']);
    }

    public function test_login_dengan_password_salah_ditolak_422(): void
    {
        User::factory()->create(['email' => 'tes@agenda.test']);

        $this->postJson('/api/login', [
            'email'    => 'tes@agenda.test',
            'password' => 'salah',
        ])->assertStatus(422);
    }

    public function test_logout_menghapus_token(): void
    {
        $user  = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $this->withToken($token)
            ->postJson('/api/logout')
            ->assertStatus(200);

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}
