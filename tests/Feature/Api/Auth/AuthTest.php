<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

Class AuthTest extends TestCase{

    use RefreshDatabase;
    public function test_user_can_login(): void{
        $user = User::factory()->create();

        $response = $this->postJson('/api/auth/login',[
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['token','user']);
    }

    public function test_user_cannot_login(): void{
        $user = User::factory()->create();

        $response = $this->postJson('/api/auth/login',[
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }
}
