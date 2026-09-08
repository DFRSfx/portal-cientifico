<?php

namespace Tests\Feature;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AuthApprovalFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_registration_requires_email_verification_and_admin_approval(): void
    {
        Notification::fake();

        $response = $this->post(route('register'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
            'type' => 'teacher',
            'entities' => ['Dept A'],
            'entidade' => 'Dept A',
        ]);

        $response->assertRedirect(route('verification.notice'));

        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);
        $this->assertNull($user->email_verified_at);
        $this->assertFalse((bool) $user->is_active);
        $this->assertSame(0, (int) $user->is_admin);
        $this->assertNotEmpty($user->set_password_token);
        $this->assertAuthenticatedAs($user);

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_login_is_blocked_when_email_not_verified(): void
    {
        $user = User::factory()
            ->unverified()
            ->create(['is_active' => 1]);

        $response = $this->post(route('login'), [
            'username' => $user->email,
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_is_blocked_when_pending_admin_approval(): void
    {
        $user = User::factory()->create(['is_active' => 0]);

        $response = $this->post(route('login'), [
            'username' => $user->email,
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_succeeds_when_verified_and_approved(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('login'), [
            'username' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(RouteServiceProvider::HOME);
        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_can_approve_pending_user(): void
    {
        $admin = User::factory()->create([
            'type' => 'administrative',
            'is_admin' => 1,
            'is_active' => 1,
        ]);

        $pendingUser = User::factory()->pendingApproval()->create();

        $response = $this->actingAs($admin)
            ->from(route('user.inactive'))
            ->post(route('user.activate', $pendingUser->id));

        $response->assertRedirect(route('user.inactive'));
        $this->assertTrue((bool) $pendingUser->fresh()->is_active);
    }

    public function test_admin_can_resend_verification_email(): void
    {
        Notification::fake();

        $admin = User::factory()->create([
            'type' => 'administrative',
            'is_admin' => 1,
            'is_active' => 1,
        ]);

        $unverifiedUser = User::factory()->unverified()->pendingApproval()->create();

        $response = $this->actingAs($admin)
            ->from(route('user.inactive'))
            ->post(route('user.resend-verification', $unverifiedUser->id));

        $response->assertRedirect(route('user.inactive'));
        Notification::assertSentTo($unverifiedUser, VerifyEmail::class);
    }
}
