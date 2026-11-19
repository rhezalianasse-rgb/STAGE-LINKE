<?php

namespace Tests\Feature;

use App\Enums\OtpSendResult;
use App\Mail\EmailVerificationCode;
use App\Models\Candidature;
use App\Models\Entreprise;
use App\Models\Offre;
use App\Models\Specialite;
use App\Models\User;
use App\Services\EmailVerificationService;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class StageLinkAuditTest extends TestCase
{
    use RefreshDatabase;

    public function test_inactive_accounts_are_rejected_and_existing_sessions_are_logged_out(): void
    {
        $inactive = User::factory()->inactive()->create([
            'email' => 'inactive@example.com',
            'mot_de_passe' => Hash::make('password123'),
        ]);

        $this->post('/login', [
            'email' => $inactive->email,
            'mot_de_passe' => 'password123',
        ])->assertSessionHasErrors('email');
        $this->assertGuest();

        $active = User::factory()->create([
            'email' => 'active@example.com',
            'mot_de_passe' => Hash::make('password123'),
        ]);
        $active->stagiaire()->create([]);

        $this->post('/login', [
            'email' => $active->email,
            'mot_de_passe' => 'password123',
        ])->assertRedirect('/stagiaire/dashboard');
        $this->assertAuthenticatedAs($active);

        $active->update(['statut' => 'inactif']);

        Auth::forgetGuards();
        $this->get('/stagiaire/dashboard')->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_offer_updates_cannot_transfer_ownership_and_inactive_offers_are_unavailable(): void
    {
        [$companyUser, $company] = $this->createCompany('company-one@example.com');
        [, $otherCompany] = $this->createCompany('company-two@example.com');
        $offer = $this->createOffer($company);

        $this->actingAs($companyUser)
            ->put('/entreprise/offres/'.$offer->id, [
                'titre' => 'Offre modifiée',
                'description' => 'Description mise à jour',
                'type_stage' => 'PFE',
                'duree' => 3,
                'localisation' => 'Rabat',
                'date_expiration' => '',
                'statut' => 'active',
                'entreprise_id' => $otherCompany->id,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('offres', [
            'id' => $offer->id,
            'entreprise_id' => $company->id,
            'titre' => 'Offre modifiée',
        ]);

        $offer->update(['statut' => 'inactive']);
        $this->get('/offres/'.$offer->id)->assertNotFound();

        [$studentUser] = $this->createStudent('student@example.com');
        $response = $this->actingAs($studentUser)
            ->post('/stagiaire/offres/'.$offer->id.'/postuler');
        $this->assertSame(404, $response->status(), $response->headers->get('Location').' '.$response->getContent());

        $this->assertDatabaseMissing('candidatures', ['offre_id' => $offer->id]);
    }

    public function test_expired_offers_are_hidden_and_not_accepting_applications(): void
    {
        [, $company] = $this->createCompany('expired-company@example.com');
        $offer = $this->createOffer($company, [
            'date_expiration' => today()->subDay()->toDateString(),
        ]);
        [$studentUser] = $this->createStudent('expired-student@example.com');

        $this->get('/offres')->assertDontSee($offer->titre);
        $this->get('/offres/'.$offer->id)->assertNotFound();
        $response = $this->actingAs($studentUser)
            ->post('/stagiaire/offres/'.$offer->id.'/postuler');
        $this->assertSame(404, $response->status(), $response->headers->get('Location').' '.$response->getContent());
    }

    public function test_public_offer_search_filters_by_company_specialty(): void
    {
        [, $webCompany] = $this->createCompany('web-company@example.com');
        [, $designCompany] = $this->createCompany('design-company@example.com');
        $webSpecialty = Specialite::create(['nom' => 'Développement Web']);
        $designSpecialty = Specialite::create(['nom' => 'Design']);
        $webCompany->specialites()->attach($webSpecialty);
        $designCompany->specialites()->attach($designSpecialty);
        $webOffer = $this->createOffer($webCompany, ['titre' => 'Offre Web']);
        $this->createOffer($designCompany, ['titre' => 'Offre Design']);

        $this->get('/offres?specialite_id='.$webSpecialty->id)
            ->assertOk()
            ->assertSee($webOffer->titre)
            ->assertDontSee('Offre Design');
    }

    public function test_login_regenerates_session_and_logout_invalidates_it(): void
    {
        $user = User::factory()->create([
            'email' => 'session@example.com',
            'mot_de_passe' => Hash::make('password123'),
        ]);
        $user->stagiaire()->create([]);

        $this->get('/login')->assertOk();
        $sessionId = $this->app['session.store']->getId();

        $this->post('/login', [
            'email' => $user->email,
            'mot_de_passe' => 'password123',
        ])->assertRedirect('/stagiaire/dashboard');

        $this->assertNotSame($sessionId, $this->app['session.store']->getId());
        $this->assertAuthenticatedAs($user);

        $this->post('/logout')->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_registration_sends_an_otp_and_keeps_the_new_account_unverified(): void
    {
        Mail::fake();
        config(['mail.default' => 'array']);

        $this->post('/register', [
            'nom' => 'Diallo',
            'prenom' => 'Aminata',
            'email' => 'new-student@example.com',
            'mot_de_passe' => 'StrongPass123!',
            'mot_de_passe_confirmation' => 'StrongPass123!',
        ])->assertRedirect(route('verification.notice'));

        $user = User::where('email', 'new-student@example.com')->firstOrFail();
        $this->assertNull($user->email_verified_at);
        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseHas('stagiaires', ['user_id' => $user->id]);
        $this->assertDatabaseHas('email_verification_otps', ['user_id' => $user->id]);

        Mail::assertSent(EmailVerificationCode::class, fn (EmailVerificationCode $mail) => $mail->hasTo($user->email)
            && preg_match('/\A[0-9]{6}\z/', $mail->code) === 1);
    }

    public function test_email_otp_is_hashed_single_use_and_gates_dashboard_access(): void
    {
        Mail::fake();
        config(['mail.default' => 'array']);

        $user = User::factory()->unverified()->create();
        $result = app(EmailVerificationService::class)->sendCode($user);
        $this->assertSame(OtpSendResult::Sent, $result);

        $code = null;
        Mail::assertSent(EmailVerificationCode::class, function (EmailVerificationCode $mail) use (&$code, $user) {
            $code = $mail->code;

            return $mail->hasTo($user->email);
        });

        $otp = $user->emailVerificationOtp()->firstOrFail();
        $this->assertNotSame($code, $otp->code_hash);
        $this->assertTrue(Hash::check($code, $otp->code_hash));

        $this->actingAs($user)
            ->get(route('stagiaire.dashboard'))
            ->assertRedirect(route('verification.notice'));

        $this->post(route('verification.verify'), ['code' => $code])
            ->assertRedirect(route('stagiaire.dashboard'));

        $this->assertNotNull($user->fresh()->email_verified_at);
        $this->assertDatabaseMissing('email_verification_otps', ['user_id' => $user->id]);

        $this->post(route('verification.verify'), ['code' => $code])
            ->assertSessionHasErrors('code');
    }

    public function test_email_otp_expires_and_locks_after_five_incorrect_attempts(): void
    {
        Mail::fake();
        config(['mail.default' => 'array']);

        $expiredUser = User::factory()->unverified()->create();
        app(EmailVerificationService::class)->sendCode($expiredUser);
        $expiredUser->emailVerificationOtp()->update(['expires_at' => now()->subSecond()]);

        $this->actingAs($expiredUser)
            ->post(route('verification.verify'), ['code' => '123456'])
            ->assertSessionHasErrors('code');
        $this->assertDatabaseMissing('email_verification_otps', ['user_id' => $expiredUser->id]);

        $this->flushSession();
        $lockedUser = User::factory()->unverified()->create();
        app(EmailVerificationService::class)->sendCode($lockedUser);
        $validCode = null;
        Mail::assertSent(EmailVerificationCode::class, function (EmailVerificationCode $mail) use (&$validCode, $lockedUser) {
            if ($mail->hasTo($lockedUser->email)) {
                $validCode = $mail->code;

                return true;
            }

            return false;
        });
        $wrongCode = $validCode === '000000' ? '000001' : '000000';

        for ($attempt = 1; $attempt < 5; $attempt++) {
            $this->actingAs($lockedUser)
                ->post(route('verification.verify'), ['code' => $wrongCode])
                ->assertSessionHasErrors('code');
        }

        $this->actingAs($lockedUser)
            ->post(route('verification.verify'), ['code' => $wrongCode])
            ->assertSessionHasErrors('code');
        $this->assertDatabaseMissing('email_verification_otps', ['user_id' => $lockedUser->id]);
    }

    public function test_otp_resend_has_a_cooldown_and_mail_configuration_is_required(): void
    {
        Mail::fake();
        config(['mail.default' => 'array']);

        $user = User::factory()->unverified()->create();
        $verification = app(EmailVerificationService::class);
        $this->assertSame(OtpSendResult::Sent, $verification->sendCode($user));
        $this->assertSame(OtpSendResult::Cooldown, $verification->sendCode($user, resend: true));

        $this->travel(61)->seconds();
        $this->assertSame(OtpSendResult::Sent, $verification->sendCode($user, resend: true));

        $unconfiguredUser = User::factory()->unverified()->create();
        config(['mail.default' => 'log']);
        $this->assertSame(OtpSendResult::MailNotConfigured, $verification->sendCode($unconfiguredUser));
        $this->assertDatabaseMissing('email_verification_otps', ['user_id' => $unconfiguredUser->id]);
    }

    public function test_google_registration_requires_a_verified_email_and_creates_only_students(): void
    {
        config([
            'services.google.client_id' => 'client-id',
            'services.google.client_secret' => 'client-secret',
            'services.google.redirect' => 'http://localhost/auth/google/callback',
        ]);
        $provider = Mockery::mock();
        $provider->shouldReceive('user')->once()->andReturn(SocialiteUser::fake([
            'id' => 'google-student-1',
            'name' => 'Google Student',
            'email' => 'google-student@example.com',
            'verified_email' => true,
            'given_name' => 'Google',
            'family_name' => 'Student',
        ]));
        Socialite::shouldReceive('driver')->once()->with('google')->andReturn($provider);

        $this->get(route('google.callback'))
            ->assertRedirect(route('stagiaire.dashboard'));

        $user = User::where('email', 'google-student@example.com')->firstOrFail();
        $this->assertAuthenticatedAs($user);
        $this->assertSame('stagiaire', $user->role);
        $this->assertNotNull($user->email_verified_at);
        $this->assertSame('google-student-1', $user->google_id);
        $this->assertDatabaseHas('stagiaires', ['user_id' => $user->id]);
    }

    public function test_google_does_not_automatically_link_an_existing_email_account(): void
    {
        config([
            'services.google.client_id' => 'client-id',
            'services.google.client_secret' => 'client-secret',
            'services.google.redirect' => 'http://localhost/auth/google/callback',
        ]);
        $existing = User::factory()->create(['email' => 'existing@example.com']);
        $provider = Mockery::mock();
        $provider->shouldReceive('user')->once()->andReturn(SocialiteUser::fake([
            'id' => 'google-existing-1',
            'name' => 'Existing User',
            'email' => $existing->email,
            'verified_email' => true,
        ]));
        Socialite::shouldReceive('driver')->once()->with('google')->andReturn($provider);

        $this->get(route('google.callback'))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertNull($existing->fresh()->google_id);
        $this->assertGuest();
    }

    public function test_google_linking_requires_an_authenticated_post_and_links_the_confirmed_identity(): void
    {
        config([
            'services.google.client_id' => 'client-id',
            'services.google.client_secret' => 'client-secret',
            'services.google.redirect' => 'http://localhost/auth/google/callback',
        ]);
        $user = User::factory()->create(['email' => 'link-google@example.com']);
        $provider = Mockery::mock();
        $provider->shouldReceive('redirect')->once()->andReturn(redirect('/mock-google'));
        $provider->shouldReceive('user')->once()->andReturn(SocialiteUser::fake([
            'id' => 'google-linked-1',
            'name' => 'Linked User',
            'email' => $user->email,
            'verified_email' => true,
        ]));
        Socialite::shouldReceive('driver')->twice()->with('google')->andReturn($provider);

        $this->actingAs($user)
            ->post(route('google.link'))
            ->assertRedirect('/mock-google')
            ->assertSessionHas('oauth.google.linking_user_id', $user->id);

        $this->get(route('google.callback'))
            ->assertRedirect(route('stagiaire.dashboard'));

        $this->assertSame('google-linked-1', $user->fresh()->google_id);
    }

    public function test_google_linking_rejects_a_different_provider_email(): void
    {
        config([
            'services.google.client_id' => 'client-id',
            'services.google.client_secret' => 'client-secret',
            'services.google.redirect' => 'http://localhost/auth/google/callback',
        ]);
        $user = User::factory()->create(['email' => 'link-google@example.com']);
        $provider = Mockery::mock();
        $provider->shouldReceive('redirect')->once()->andReturn(redirect('/mock-google'));
        $provider->shouldReceive('user')->once()->andReturn(SocialiteUser::fake([
            'id' => 'google-mismatch-1',
            'name' => 'Different User',
            'email' => 'different-google@example.com',
            'verified_email' => true,
        ]));
        Socialite::shouldReceive('driver')->twice()->with('google')->andReturn($provider);

        $this->actingAs($user)
            ->post(route('google.link'))
            ->assertRedirect('/mock-google');

        $this->get(route('google.callback'))
            ->assertRedirect(route('stagiaire.dashboard'))
            ->assertSessionHas('error');

        $this->assertNull($user->fresh()->google_id);
    }

    public function test_google_rejects_unverified_provider_email_and_invalid_oauth_state(): void
    {
        config([
            'services.google.client_id' => 'client-id',
            'services.google.client_secret' => 'client-secret',
            'services.google.redirect' => 'http://localhost/auth/google/callback',
        ]);
        $unverifiedProvider = Mockery::mock();
        $unverifiedProvider->shouldReceive('user')->once()->andReturn(SocialiteUser::fake([
            'id' => 'google-unverified-1',
            'name' => 'Unverified User',
            'email' => 'unverified-google@example.com',
            'verified_email' => false,
        ]));
        Socialite::shouldReceive('driver')->once()->with('google')->andReturn($unverifiedProvider);

        $this->get(route('google.callback'))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('users', ['email' => 'unverified-google@example.com']);

        $invalidStateProvider = Mockery::mock();
        $invalidStateProvider->shouldReceive('user')->once()->andThrow(new InvalidStateException());
        Socialite::shouldReceive('driver')->once()->with('google')->andReturn($invalidStateProvider);

        $this->get(route('google.callback'))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');
    }

    public function test_password_reset_invalidates_sessions_that_used_the_old_password(): void
    {
        $user = User::factory()->create([
            'email' => 'session-reset@example.com',
            'mot_de_passe' => Hash::make('OldPassword123'),
        ]);
        $user->stagiaire()->create([]);

        $this->actingAs($user);

        $token = Password::createToken($user);
        $this->post(route('password.update'), [
            'email' => $user->email,
            'token' => $token,
            'mot_de_passe' => 'NewPassword123!',
            'mot_de_passe_confirmation' => 'NewPassword123!',
        ])->assertRedirect(route('login'));

        Auth::forgetGuards();
        $this->get(route('stagiaire.dashboard'))->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_cv_files_are_private_and_downloads_are_scoped_to_the_related_users(): void
    {
        Storage::fake('private');
        Storage::fake('public');

        [$studentUser, $student] = $this->createStudent('cv-student@example.com');

        $this->actingAs($studentUser)
            ->put('/stagiaire/profil', [
                'nom' => $studentUser->nom,
                'prenom' => $studentUser->prenom,
                'cv' => UploadedFile::fake()->create('resume.pdf', 10, 'application/pdf'),
            ])
            ->assertRedirect();

        $student->refresh();
        $this->assertNotEmpty($student->cv_path);
        Storage::disk('private')->assertExists($student->cv_path);
        Storage::disk('public')->assertMissing($student->cv_path);

        $this->actingAs($studentUser)
            ->get(route('stagiaire.cv'))
            ->assertOk()
            ->assertHeader('content-disposition');

        [$companyUser, $company] = $this->createCompany('cv-company@example.com');
        $offer = $this->createOffer($company);
        $application = Candidature::create([
            'stagiaire_id' => $student->id,
            'offre_id' => $offer->id,
            'date_candidature' => today(),
            'statut' => 'en_attente',
            'cv_path' => $student->cv_path,
        ]);

        $this->actingAs($companyUser)
            ->get(route('entreprise.candidatures.cv', $application))
            ->assertOk();

        [$otherCompanyUser] = $this->createCompany('cv-other-company@example.com');
        $this->actingAs($otherCompanyUser)
            ->get(route('entreprise.candidatures.cv', $application))
            ->assertNotFound();

        $admin = User::factory()->create([
            'email' => 'cv-admin@example.com',
            'role' => 'admin',
        ]);
        $this->actingAs($admin)
            ->get(route('admin.stagiaires.cv', $student->id))
            ->assertOk();
    }

    public function test_profile_photos_are_served_from_the_public_disk_without_a_storage_symlink(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('photos/avatar.jpg', 'photo bytes');

        $this->get(route('profile-photos.show', ['filename' => 'avatar.jpg']))
            ->assertOk()
            ->assertStreamedContent('photo bytes');

        $this->get(route('profile-photos.show', ['filename' => '..']))
            ->assertNotFound();

        $this->get(route('profile-photos.show', ['filename' => 'missing.jpg']))
            ->assertNotFound();
    }

    public function test_public_cv_files_can_be_moved_without_losing_their_contents(): void
    {
        Storage::fake('public');
        Storage::fake('private');
        Storage::disk('public')->put('cvs/legacy.pdf', 'existing-cv-content');

        $this->artisan('stagelink:protect-cvs')->assertExitCode(0);

        Storage::disk('private')->assertExists('cvs/legacy.pdf');
        Storage::disk('public')->assertMissing('cvs/legacy.pdf');
        $this->assertSame('existing-cv-content', Storage::disk('private')->get('cvs/legacy.pdf'));
    }

    public function test_password_reset_uses_the_custom_password_column(): void
    {
        NotificationFacade::fake();
        config(['mail.default' => 'array']);
        $user = User::factory()->create([
            'email' => 'reset@example.com',
            'mot_de_passe' => Hash::make('old-password'),
        ]);

        $this->post(route('password.email'), ['email' => $user->email])
            ->assertRedirect()
            ->assertSessionHas('status');

        $token = null;
        NotificationFacade::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use (&$token) {
            $token = $notification->token;

            return true;
        });

        $this->post(route('password.update'), [
            'email' => $user->email,
            'token' => $token,
            'mot_de_passe' => 'NewSecurePass123!',
            'mot_de_passe_confirmation' => 'NewSecurePass123!',
        ])->assertRedirect(route('login'));

        $this->assertTrue(Hash::check('NewSecurePass123!', $user->fresh()->mot_de_passe));
    }

    public function test_password_reset_does_not_write_reset_links_to_the_log_mailer(): void
    {
        NotificationFacade::fake();
        config(['mail.default' => 'log']);
        $user = User::factory()->create(['email' => 'no-mail-reset@example.com']);

        $this->post(route('password.email'), ['email' => $user->email])
            ->assertRedirect()
            ->assertSessionHas('error')
            ->assertSessionMissing('status');

        NotificationFacade::assertNothingSent();
    }

    public function test_user_factory_creates_records_matching_the_custom_schema(): void
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'nom' => $user->nom,
            'prenom' => $user->prenom,
            'role' => 'stagiaire',
            'statut' => 'actif',
        ]);
    }

    public function test_error_pages_render_without_exception_details(): void
    {
        $this->get('/missing-audit-page')
            ->assertNotFound()
            ->assertSee('Page introuvable');

        $this->view('errors.500')
            ->assertSee('Une erreur est survenue')
            ->assertDontSee('SQLSTATE');
    }

    private function createCompany(string $email): array
    {
        $user = User::factory()->create([
            'email' => $email,
            'role' => 'entreprise',
        ]);

        return [$user, $user->entreprise()->create(['nom_entreprise' => 'Stage Link'])];
    }

    private function createStudent(string $email): array
    {
        $user = User::factory()->create([
            'email' => $email,
            'role' => 'stagiaire',
        ]);

        return [$user, $user->stagiaire()->create([])];
    }

    private function createOffer(Entreprise $company, array $attributes = []): Offre
    {
        return $company->offres()->create(array_merge([
            'titre' => 'Stage à pourvoir',
            'description' => 'Description de l’offre',
            'type_stage' => 'PFE',
            'duree' => 3,
            'localisation' => 'Casablanca',
            'date_publication' => today(),
            'statut' => 'active',
        ], $attributes));
    }
}
