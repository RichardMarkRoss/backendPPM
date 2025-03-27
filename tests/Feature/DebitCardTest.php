<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\DebitCard;
use PHPUnit\Framework\Attributes\Test;
class DebitCardTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('API Token')->plainTextToken;
        
    }

    #[Test]
    public function user_can_create_a_debit_card()
    {
        $response = $this->postJson('/api/debit-cards', [
            'card_number' => '1234567812345678',
            'balance' => 1000.00,
            'status' => 'active'
        ], ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(201);
    }

    #[Test]
    public function user_can_view_their_debit_cards()
    {
        DebitCard::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson('/api/debit-cards', [
            'Authorization' => "Bearer $this->token"
        ]);

        $response->assertStatus(201);
    }

    #[Test]
    public function user_cannot_view_other_users_debit_cards()
    {
        $anotherUser = User::factory()->create();
        DebitCard::factory()->create(['user_id' => $anotherUser->id]);

        $response = $this->getJson('/api/debit-cards', [
            'Authorization' => "Bearer $this->token"
        ]);
        dd($response);
        $response->assertStatus(201)->assertJson([]);
    }
}