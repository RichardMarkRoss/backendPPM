<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Loan;
use PHPUnit\Framework\Attributes\Test;
class LoanTest extends TestCase
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
    public function user_can_apply_for_a_loan()
    {
        $response = $this->postJson('/api/loans', [
            'amount' => 3000,
            'term' => 3
        ], ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['message', 'loan']);
    }

    #[Test]
    public function user_can_view_their_loans()
    {
        Loan::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson('/api/loans', [
            'Authorization' => "Bearer $this->token"
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([['id', 'amount', 'remaining_balance', 'status']]);
    }

    #[Test]
    public function user_can_make_a_loan_repayment()
    {
        $loan = Loan::factory()->create(['user_id' => $this->user->id, 'remaining_balance' => 3000]);

        $response = $this->postJson("/api/loans/{$loan->id}/repayments", [
            'amount' => 1000
        ], ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['message', 'remaining_balance']);
    }
}
