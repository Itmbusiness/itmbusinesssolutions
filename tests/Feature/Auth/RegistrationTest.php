<?php

namespace Tests\Feature\Auth;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Fortify\Features;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->skipUnlessFortifyHas(Features::registration());
    }

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get(route('register'));

        $response->assertOk();
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'John Doe',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();
    }

    public function test_the_registration_screen_offers_both_account_types(): void
    {
        $this->withoutVite()
            ->get(route('register'))
            ->assertOk()
            ->assertSee('I am registering as')
            ->assertSee('Consultant or agent');
    }

    public function test_a_user_registering_without_choosing_a_role_becomes_a_client(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Jane Doe',
            'email' => 'client@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertSessionHasNoErrors();

        $this->assertSame(UserRole::Client, User::where('email', 'client@example.com')->sole()->role);
    }

    public function test_a_user_can_register_as_a_consultant(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Agent Smith',
            'email' => 'agent@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'consultant',
        ])->assertSessionHasNoErrors();

        $this->assertSame(UserRole::Consultant, User::where('email', 'agent@example.com')->sole()->role);
    }

    public function test_an_unknown_role_is_rejected(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Sneaky',
            'email' => 'sneaky@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'administrator',
        ])->assertSessionHasErrors('role');

        $this->assertDatabaseMissing('users', ['email' => 'sneaky@example.com']);
    }
}
