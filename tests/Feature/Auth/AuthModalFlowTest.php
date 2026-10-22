<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class AuthModalFlowTest extends TestCase
{
    use RefreshDatabase;

    private function registration(array $overrides = []): array
    {
        return array_replace([
            'name' => 'Pessoa de teste', 'email' => 'nova@example.com',
            'job_role' => 'dev-frontend', 'terms' => true,
            'terms_version' => config('legal.version'), 'privacy_notice_version' => config('legal.version'),
            'password' => 'nova-senha-123', 'password_confirmation' => 'nova-senha-123',
        ], $overrides);
    }

    public function test_direct_guest_routes_open_the_matching_modal_over_the_landing_page(): void
    {
        foreach (['/login' => 'login', '/register' => 'register', '/forgot-password' => 'forgot-password', '/reset-password/example-token?email=pessoa%40example.com' => 'reset-password', '/reset-password/example-token?email[]=invalid' => 'reset-password'] as $path => $screen) {
            $this->get($path)->assertOk()->assertViewIs('welcome')->assertViewHas('authScreen', $screen)->assertSee('data-auth-panel="'.$screen.'"', false);
        }
    }

    public function test_json_registration_sends_verification_and_does_not_unlock_the_dashboard(): void
    {
        Notification::fake();
        $this->postJson('/register', $this->registration(['email' => '  NOVA@example.com  ']))
            ->assertOk()->assertJson(['screen' => 'verify-email', 'authenticated' => true, 'verified' => false])->assertJsonStructure(['csrf']);
        $user = User::where('email', 'nova@example.com')->firstOrFail();
        $this->assertAuthenticatedAs($user);
        $this->assertSame('dev-frontend', $user->job_role);
        $this->assertNotNull($user->terms_accepted_at);
        $this->assertNull($user->email_verified_at);
        $this->assertTrue(Hash::check('nova-senha-123', $user->password));
        Notification::assertSentTo($user, VerifyEmail::class);
        foreach (['/dashboard', '/profile', '/admin/plans'] as $path) {
            $this->get($path)->assertRedirect(route('verification.notice'));
        }
    }

    public function test_registration_validates_role_terms_and_password_confirmation(): void
    {
        $this->postJson('/register', $this->registration(['job_role' => 'admin', 'terms' => false, 'password_confirmation' => 'diferente']))
            ->assertUnprocessable()->assertJsonValidationErrors(['job_role', 'terms', 'password']);
        $this->assertDatabaseCount('users', 0);
    }

    public function test_registration_rejects_duplicate_email_and_malformed_values(): void
    {
        User::factory()->create(['email' => 'nova@example.com']);
        $this->postJson('/register', $this->registration())->assertUnprocessable()->assertJsonValidationErrors('email');
        $this->postJson('/register', $this->registration(['email' => ['invalid']]))->assertUnprocessable()->assertJsonValidationErrors('email');
    }

    public function test_unverified_login_stays_in_the_verification_flow(): void
    {
        $user = User::factory()->unverified()->create();
        $this->postJson('/login', ['email' => strtoupper($user->email), 'password' => 'password'])
            ->assertOk()->assertJson(['screen' => 'verify-email', 'verified' => false]);
        $this->get('/dashboard')->assertRedirect(route('verification.notice'));
    }

    public function test_password_recovery_does_not_disclose_whether_an_address_exists(): void
    {
        Notification::fake();
        $user = User::factory()->create();
        $known = $this->postJson('/forgot-password', ['email' => $user->email])->assertOk()->assertJson(['screen' => 'reset-link-sent']);
        $unknown = $this->postJson('/forgot-password', ['email' => 'desconhecido@example.com'])->assertOk()->assertJson(['screen' => 'reset-link-sent']);
        $this->assertSame($known->json('message'), $unknown->json('message'));
        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_json_password_reset_changes_the_password_and_consumes_the_token(): void
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);
        $payload = ['token' => $token, 'email' => $user->email, 'password' => 'outra-senha-123', 'password_confirmation' => 'outra-senha-123'];
        $this->postJson('/reset-password', $payload)->assertOk()->assertJson(['screen' => 'password-reset']);
        $this->assertTrue(Hash::check('outra-senha-123', $user->fresh()->password));
        $this->postJson('/reset-password', $payload)->assertUnprocessable()->assertJsonValidationErrors('email');
    }

    public function test_expired_password_reset_tokens_are_rejected(): void
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);
        $this->travel(61)->minutes();
        $this->postJson('/reset-password', ['token' => $token, 'email' => $user->email, 'password' => 'outra-senha-123', 'password_confirmation' => 'outra-senha-123'])
            ->assertUnprocessable()->assertJsonValidationErrors('email');
        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }

    public function test_verification_resend_check_and_logout_work_without_page_redirects(): void
    {
        Notification::fake();
        $user = User::factory()->unverified()->create();
        $this->actingAs($user)->postJson('/email/verification-notification')->assertOk()->assertJson(['screen' => 'verify-email']);
        Notification::assertSentTo($user, VerifyEmail::class);
        $this->getJson('/verify-email')->assertOk()->assertJson(['screen' => 'verify-email']);
        $user->markEmailAsVerified();
        $this->getJson('/verify-email')->assertOk()->assertJson(['screen' => 'email-verified']);
        $this->postJson('/logout')->assertOk()->assertJson(['screen' => 'login', 'authenticated' => false])->assertJsonStructure(['csrf']);
        $this->assertGuest();
    }

    public function test_expired_verification_links_open_an_error_modal_without_verifying_the_account(): void
    {
        $user = User::factory()->unverified()->create();
        $url = URL::temporarySignedRoute('verification.verify', now()->subMinute(), ['id' => $user->id, 'hash' => sha1($user->email)]);
        $this->actingAs($user)->get($url)->assertForbidden()->assertViewIs('welcome')->assertViewHas('authScreen', 'verification-invalid');
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    public function test_login_after_opening_a_verification_link_preserves_the_signed_destination(): void
    {
        $user = User::factory()->unverified()->create();
        $url = URL::temporarySignedRoute('verification.verify', now()->addHour(), ['id' => $user->id, 'hash' => sha1($user->email)]);
        $this->get($url)->assertRedirect(route('login'));
        $this->postJson('/login', ['email' => $user->email, 'password' => 'password'])->assertOk()->assertJson(['redirect' => $url]);
        $this->get($url)->assertOk()->assertViewHas('authScreen', 'email-verified');
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }

    public function test_confirm_password_supports_json_validation_and_success(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->postJson('/confirm-password', ['password' => 'errada'])->assertUnprocessable()->assertJsonValidationErrors('password');
        $this->postJson('/confirm-password', ['password' => 'password'])->assertOk()->assertJsonStructure(['redirect']);
        $this->assertNotNull(session('auth.password_confirmed_at'));
    }

    public function test_email_templates_use_the_project_brand_and_real_action_urls(): void
    {
        $user = User::factory()->create();
        $verification = (new VerifyEmail)->toMail($user)->render();
        $reset = (new ResetPassword('test-token'))->toMail($user)->render();
        $this->assertStringContainsString('Confirme seu e-mail', $verification);
        $this->assertStringContainsString('/verify-email/', $verification);
        $this->assertStringContainsString('Redefina sua senha', $reset);
        $this->assertStringContainsString('/reset-password/test-token', $reset);
    }
}
