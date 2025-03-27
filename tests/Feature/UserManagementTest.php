<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $managerUser;
    protected $regularUser;

    protected function setUp(): void
    {
        parent::setUp();
    
        // Run migrations and seed the roles table
        $this->artisan('migrate:fresh --seed');
    
        // Fetch roles
        $adminRole = Role::where('name', 'Admin')->first();
        $managerRole = Role::where('name', 'Manager')->first();
        $userRole = Role::where('name', 'User')->first();
    
        // Ensure roles exist before assigning
        if (!$adminRole || !$managerRole || !$userRole) {
            throw new \Exception("Roles not found! Check if RolesSeeder is properly seeding.");
        }
    
        // Create users with correct role IDs
        $this->adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
        ]);
    
        $this->managerUser = User::factory()->create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
            'role_id' => $managerRole->id,
        ]);
    
        $this->regularUser = User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role_id' => $userRole->id,
        ]);
    }
    

    /** @test */
    public function admin_can_create_a_user()
    {
        Sanctum::actingAs($this->adminUser);

        $response = $this->postJson('/api/users', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role_id' => $this->regularUser->role_id,
        ]);

        $response->assertStatus(201)
                 ->assertJson(['message' => 'User created successfully']);

        $this->assertDatabaseHas('users', ['email' => 'newuser@example.com']);
    }

    /** @test */
    public function manager_can_create_a_user_but_not_admin()
    {
        Sanctum::actingAs($this->managerUser);

        $response = $this->postJson('/api/users', [
            'name' => 'New Manager User',
            'email' => 'newmanager@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role_id' => $this->adminUser->role_id, // Trying to assign Admin role
        ]);

        $response->assertStatus(403); // Should be forbidden
    }

    /** @test */
    public function regular_user_cannot_create_users()
    {
        Sanctum::actingAs($this->regularUser);

        $response = $this->postJson('/api/users', [
            'name' => 'Unauthorized User',
            'email' => 'unauthorized@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role_id' => $this->regularUser->role_id,
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_update_a_users_role()
    {
        Sanctum::actingAs($this->adminUser);

        $response = $this->putJson("/api/users/{$this->regularUser->id}/role", [
            'role_id' => $this->managerUser->role_id,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'User role updated successfully']);

        $this->assertDatabaseHas('users', [
            'id' => $this->regularUser->id,
            'role_id' => $this->managerUser->role_id,
        ]);
    }

    /** @test */
    public function manager_cannot_update_user_roles()
    {
        Sanctum::actingAs($this->managerUser);

        $response = $this->putJson("/api/users/{$this->regularUser->id}/role", [
            'role_id' => $this->adminUser->role_id,
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_delete_a_user()
    {
        Sanctum::actingAs($this->adminUser);

        $response = $this->deleteJson("/api/users/{$this->regularUser->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'User deleted successfully']);

        $this->assertDatabaseMissing('users', ['id' => $this->regularUser->id]);
    }

    /** @test */
    public function regular_user_cannot_delete_users()
    {
        Sanctum::actingAs($this->regularUser);

        $response = $this->deleteJson("/api/users/{$this->adminUser->id}");

        $response->assertStatus(403);
    }
}
