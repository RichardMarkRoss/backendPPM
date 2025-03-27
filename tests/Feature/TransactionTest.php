<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\DebitCard;
use App\Models\Transaction;
use PHPUnit\Framework\Attributes\Test;
class TransactionTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;
    protected $debitCard;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('API Token')->plainTextToken;
        $this->debitCard = DebitCard::factory()->create(['user_id' => $this->user->id, 'balance' => 1000]);
    }

    #[Test]
    public function user_can_make_a_deposit()
    {
        $response = $this->postJson('/api/transactions', [
            'debit_card_id' => $this->debitCard->id,
            'amount' => 500,
            'type' => 'deposit'
        ], ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['message', 'transaction']);
    }

    #[Test]
    public function user_cannot_withdraw_more_than_balance()
    {
        $response = $this->postJson('/api/transactions', [
            'debit_card_id' => $this->debitCard->id,
            'amount' => 2000,
            'type' => 'withdrawal'
        ], ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(400)
                 ->assertJson(['message' => 'Insufficient balance']);
    }

    #[Test]
    public function user_can_view_their_transactions()
    {
        Transaction::factory()->create(['debit_card_id' => $this->debitCard->id]);

        $response = $this->getJson('/api/transactions', [
            'Authorization' => "Bearer $this->token"
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([['id', 'amount', 'type', 'status']]);
    }
}
