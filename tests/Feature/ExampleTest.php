<?php

namespace Tests\Feature;

use App\Models\Candidature;
use App\Models\Competence;
use App\Models\Notification;
use App\Models\Offre;
use App\Models\User;
use App\Mail\EmailVerificationCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSee('data-theme-toggle', false)
            ->assertSee("localStorage.getItem('stage-link-theme')", false);
    }

    public function test_stagiaire_can_register(): void
    {
        Mail::fake();
        config(['mail.default' => 'array']);

        $response = $this->post('/register', [
            'nom' => 'Diallo',
            'prenom' => 'Aminata',
            'email' => 'aminata@example.com',
            'mot_de_passe' => 'StrongPass123!',
            'mot_de_passe_confirmation' => 'StrongPass123!',
        ]);

        $response->assertRedirect(route('verification.notice'));
        $user = User::where('email', 'aminata@example.com')->firstOrFail();
        $this->assertNull($user->email_verified_at);
        $this->assertDatabaseHas('stagiaires', ['user_id' => $user->id]);
        Mail::assertSent(EmailVerificationCode::class);
    }

    public function test_public_registration_cannot_create_entreprise(): void
    {
        $this->get('/register')
            ->assertOk()
            ->assertDontSee('name="role"', false)
            ->assertDontSee('name="nom_entreprise"', false);

        $this->from('/register')->post('/register', [
            'nom' => 'Martin',
            'prenom' => 'Lea',
            'nom_entreprise' => 'Stage Link SARL',
            'email' => 'contact@stagelink.example',
            'mot_de_passe' => 'password123',
            'mot_de_passe_confirmation' => 'password123',
            'role' => 'entreprise',
        ])
            ->assertRedirect('/register')
            ->assertSessionHasErrors('role');

        $this->assertDatabaseMissing('users', ['email' => 'contact@stagelink.example']);
        $this->assertDatabaseMissing('entreprises', ['nom_entreprise' => 'Stage Link SARL']);
    }

    public function test_only_admin_can_create_entreprise_accounts(): void
    {
        $this->get('/admin/entreprises/create')->assertRedirect('/login');

        $stagiaire = User::create([
            'nom' => 'Diallo',
            'prenom' => 'Aminata',
            'email' => 'non-admin@example.com',
            'mot_de_passe' => 'password123',
            'role' => 'stagiaire',
            'email_verified_at' => now(),
        ]);
        $this->actingAs($stagiaire)->get('/admin/entreprises/create')->assertForbidden();
        $this->actingAs($stagiaire)->post('/admin/entreprises', [])->assertForbidden();

        $admin = User::create([
            'nom' => 'Admin',
            'prenom' => 'Super',
            'email' => 'admin-create@example.com',
            'mot_de_passe' => 'password123',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get('/admin/entreprises/create')
            ->assertOk()
            ->assertSee('Ajouter une entreprise');

        $this->actingAs($admin)
            ->post('/admin/entreprises', [
                'nom_entreprise' => 'Stage Link SARL',
                'nom' => 'Martin',
                'prenom' => 'Lea',
                'email' => 'contact@stagelink.example',
                'telephone' => '0612345678',
                'secteur_activite' => 'Informatique',
                'mot_de_passe' => 'CompanyPassword123!',
                'mot_de_passe_confirmation' => 'CompanyPassword123!',
            ])
            ->assertRedirect('/admin/entreprises');

        $companyUser = User::where('email', 'contact@stagelink.example')->firstOrFail();
        $this->assertSame('entreprise', $companyUser->role);
        $this->assertTrue(Hash::check('CompanyPassword123!', $companyUser->mot_de_passe));
        $this->assertDatabaseHas('entreprises', [
            'user_id' => $companyUser->id,
            'nom_entreprise' => 'Stage Link SARL',
        ]);

        $this->actingAs($admin)
            ->get('/admin/entreprises')
            ->assertOk()
            ->assertSee('Stage Link SARL');
    }

    public function test_admin_dashboard_and_offers_page_load(): void
    {
        $admin = User::create([
            'nom' => 'Admin',
            'prenom' => 'Super',
            'email' => 'admin-pages@example.com',
            'mot_de_passe' => 'password123',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get('/admin/dashboard')
            ->assertOk()
            ->assertSee('Tableau de Bord');

        $this->actingAs($admin)
            ->get('/admin/offres')
            ->assertOk()
            ->assertSee('Gestion des Offres');
    }

    public function test_admin_management_pages_render_with_existing_records(): void
    {
        $admin = User::create([
            'nom' => 'Admin',
            'prenom' => 'Super',
            'email' => 'admin-management@example.com',
            'mot_de_passe' => 'password123',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($admin)
            ->post('/admin/competences', ['nom' => 'PHP', 'description' => 'Backend'])
            ->assertRedirect();
        $this->actingAs($admin)
            ->get('/admin/competences')
            ->assertOk()
            ->assertSee('PHP');

        $this->actingAs($admin)
            ->post('/admin/specialites', ['nom' => 'Développement Web', 'description' => 'Web'])
            ->assertRedirect();
        $this->actingAs($admin)
            ->get('/admin/specialites')
            ->assertOk()
            ->assertSee('Développement Web');

        $stagiaireUser = User::create([
            'nom' => 'Diallo',
            'prenom' => 'Aminata',
            'email' => 'admin-list-student@example.com',
            'mot_de_passe' => 'password123',
            'role' => 'stagiaire',
            'email_verified_at' => now(),
        ]);
        $stagiaire = $stagiaireUser->stagiaire()->create([]);
        $companyUser = User::create([
            'nom' => 'Martin',
            'prenom' => 'Lea',
            'email' => 'admin-list-company@example.com',
            'mot_de_passe' => 'password123',
            'role' => 'entreprise',
            'email_verified_at' => now(),
        ]);
        $entreprise = $companyUser->entreprise()->create(['nom_entreprise' => 'Stage Link']);
        $offre = $entreprise->offres()->create([
            'titre' => 'Stage admin test',
            'description' => 'Description',
            'type_stage' => 'PFE',
            'duree' => 3,
            'localisation' => 'Tunis',
            'date_publication' => now()->toDateString(),
            'statut' => 'active',
        ]);
        Candidature::create([
            'stagiaire_id' => $stagiaire->id,
            'offre_id' => $offre->id,
            'date_candidature' => now()->toDateString(),
            'statut' => 'en_attente',
        ]);

        $this->actingAs($admin)
            ->get('/admin/stagiaires')
            ->assertOk()
            ->assertSee('Aminata');
        $this->actingAs($admin)
            ->get('/admin/stagiaires/'.$stagiaire->id)
            ->assertOk()
            ->assertSee('Profil du Stagiaire');
        $this->actingAs($admin)
            ->get('/admin/candidatures')
            ->assertOk()
            ->assertSee('Aminata')
            ->assertSee('Stage admin test')
            ->assertSee('/admin/candidatures/1');
    }

    public function test_entreprise_can_publish_offer_with_create_form_fields(): void
    {
        $user = User::create([
            'nom' => 'Martin',
            'prenom' => 'Lea',
            'email' => 'publish-offer@example.com',
            'mot_de_passe' => 'password123',
            'role' => 'entreprise',
            'email_verified_at' => now(),
        ]);
        $entreprise = $user->entreprise()->create([
            'nom_entreprise' => 'Stage Link SARL',
        ]);

        $this->actingAs($user)
            ->post('/entreprise/offres', [
                'titre' => 'Développeur stagiaire',
                'description' => 'Stage de développement web',
                'type_stage' => 'PFE',
                'duree' => 3,
                'localisation' => 'Tunis',
            ])
            ->assertRedirect('/entreprise/dashboard');

        $this->assertDatabaseHas('offres', [
            'entreprise_id' => $entreprise->id,
            'titre' => 'Développeur stagiaire',
            'statut' => 'active',
        ]);
        $offre = Offre::where('titre', 'Développeur stagiaire')->firstOrFail();
        $this->assertSame(now()->toDateString(), $offre->date_publication->toDateString());
    }

    public function test_entreprise_dashboard_and_applications_use_company_relationships(): void
    {
        $entrepriseUser = User::create([
            'nom' => 'Martin',
            'prenom' => 'Lea',
            'email' => 'company-dashboard@example.com',
            'mot_de_passe' => 'password123',
            'role' => 'entreprise',
            'email_verified_at' => now(),
        ]);
        $entreprise = $entrepriseUser->entreprise()->create([
            'nom_entreprise' => 'Stage Link SARL',
        ]);
        $offre = $entreprise->offres()->create([
            'titre' => 'Développeur stagiaire',
            'description' => 'Stage de développement',
            'type_stage' => 'PFE',
            'duree' => 3,
            'localisation' => 'Tunis',
            'date_publication' => now()->toDateString(),
            'statut' => 'active',
        ]);

        $stagiaireUser = User::create([
            'nom' => 'Diallo',
            'prenom' => 'Aminata',
            'email' => 'applicant@example.com',
            'mot_de_passe' => 'password123',
            'role' => 'stagiaire',
            'email_verified_at' => now(),
        ]);
        $stagiaire = $stagiaireUser->stagiaire()->create([]);
        $candidature = Candidature::create([
            'stagiaire_id' => $stagiaire->id,
            'offre_id' => $offre->id,
            'date_candidature' => now()->toDateString(),
            'statut' => 'en_attente',
        ]);

        $this->actingAs($entrepriseUser)
            ->get('/entreprise/dashboard')
            ->assertOk()
            ->assertSee('Développeur stagiaire')
            ->assertViewHas('stats', fn ($stats) => $stats['offres_publiees'] === 1
                && $stats['candidatures_recues'] === 1
                && $stats['candidatures_attente'] === 1
                && $stats['candidatures_acceptees'] === 0);

        $this->actingAs($entrepriseUser)
            ->get('/entreprise/offres')
            ->assertOk()
            ->assertSee('Développeur stagiaire');

        $this->actingAs($entrepriseUser)
            ->get('/mes-offres')
            ->assertRedirect('/entreprise/offres');

        $this->actingAs($entrepriseUser)
            ->get('/entreprise/candidatures')
            ->assertOk()
            ->assertSee('Aminata Diallo');

        $this->actingAs($entrepriseUser)
            ->get('/entreprise/candidatures/'.$candidature->id)
            ->assertOk()
            ->assertSeeText('Profil du candidat')
            ->assertSee('Aminata Diallo');

        $this->actingAs($entrepriseUser)
            ->get('/candidatures-recues')
            ->assertRedirect('/entreprise/candidatures');

        $this->actingAs($entrepriseUser)
            ->put('/entreprise/candidatures/'.$candidature->id, ['statut' => 'acceptee'])
            ->assertRedirect();

        $this->assertDatabaseHas('notifications', [
            'utilisateur_id' => $stagiaireUser->id,
            'type' => 'candidature',
        ]);

        $notification = Notification::where('utilisateur_id', $stagiaireUser->id)->firstOrFail();
        $this->actingAs($stagiaireUser)
            ->get('/notifications')
            ->assertOk()
            ->assertSee($notification->message)
            ->assertSee('Tout marquer comme lu')
            ->assertSee('name="_method" value="PATCH"', false);

        $this->actingAs($stagiaireUser)
            ->patch('/notifications/'.$notification->id.'/read')
            ->assertRedirect();
        $this->assertDatabaseHas('notifications', ['id' => $notification->id, 'lu' => true]);

        $notification->update(['lu' => false]);
        $this->actingAs($stagiaireUser)
            ->post('/notifications/read-all', ['_method' => 'PATCH'])
            ->assertRedirect();
        $this->assertDatabaseHas('notifications', ['id' => $notification->id, 'lu' => true]);
    }

    public function test_entreprise_can_update_and_display_logo(): void
    {
        Storage::fake('public');

        $user = User::create([
            'nom' => 'Martin',
            'prenom' => 'Lea',
            'email' => 'company-logo@example.com',
            'mot_de_passe' => 'password123',
            'role' => 'entreprise',
            'email_verified_at' => now(),
        ]);
        $entreprise = $user->entreprise()->create([
            'nom_entreprise' => 'Stage Link SARL',
        ]);

        $this->actingAs($user)
            ->put('/entreprise/profil', [
                'nom' => 'Stage Link Nouvelle',
                'secteur_activite' => 'Informatique',
                'logo' => UploadedFile::fake()->create('logo.png', 1, 'image/png'),
            ])
            ->assertRedirect();

        $entreprise->refresh();
        $this->assertSame('Stage Link Nouvelle', $entreprise->nom_entreprise);
        $this->assertNotEmpty($entreprise->logo_path);
        Storage::disk('public')->assertExists($entreprise->logo_path);

        $this->actingAs($user)
            ->get('/entreprise/profil')
            ->assertOk()
            ->assertSee('storage/'.$entreprise->logo_path);
    }

    public function test_stagiaire_dashboard_loads(): void
    {
        $user = User::create([
            'nom' => 'Diallo',
            'prenom' => 'Aminata',
            'email' => 'dashboard@example.com',
            'mot_de_passe' => 'password123',
            'role' => 'stagiaire',
            'email_verified_at' => now(),
        ]);
        $user->stagiaire()->create([]);

        $this->actingAs($user)
            ->get('/profil')
            ->assertRedirect('/stagiaire/profil');

        $this->actingAs($user)
            ->followingRedirects()
            ->get('/profil')
            ->assertOk()
            ->assertSee('Mon Profil');

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertRedirect('/stagiaire/dashboard');

        $this->actingAs($user)
            ->get('/stagiaire/dashboard')
            ->assertOk()
            ->assertSeeText('Total candidatures');

        $this->actingAs($user)
            ->get('/stagiaire/candidatures')
            ->assertOk()
            ->assertSee('Mes Candidatures');

        $this->actingAs($user)
            ->get('/mes-candidatures')
            ->assertRedirect('/stagiaire/candidatures');

        $this->actingAs($user)
            ->get('/stagiaire/favoris')
            ->assertOk()
            ->assertSeeText('Mes Favoris');

        $this->actingAs($user)
            ->get('/mes-favoris')
            ->assertRedirect('/stagiaire/favoris');
    }

    public function test_stagiaire_can_update_profile_photo(): void
    {
        Storage::fake('public');

        $user = User::create([
            'nom' => 'Diallo',
            'prenom' => 'Aminata',
            'email' => 'photo@example.com',
            'mot_de_passe' => 'password123',
            'role' => 'stagiaire',
            'email_verified_at' => now(),
        ]);
        $stagiaire = $user->stagiaire()->create([]);

        $this->actingAs($user)
            ->put('/stagiaire/profil', [
                'nom' => 'Diallo',
                'prenom' => 'Aminata',
                'photo' => UploadedFile::fake()->create('profile.png', 1, 'image/png'),
            ])
            ->assertRedirect();

        $stagiaire->refresh();
        $this->assertNotEmpty($stagiaire->photo_path);
        Storage::disk('public')->assertExists($stagiaire->photo_path);

        $this->actingAs($user)
            ->get('/stagiaire/profil')
            ->assertOk()
            ->assertSee(route('profile-photos.show', ['filename' => basename($stagiaire->photo_path)]), false);
    }

    public function test_stagiaire_can_add_available_competence(): void
    {
        $user = User::create([
            'nom' => 'Diallo',
            'prenom' => 'Aminata',
            'email' => 'competence@example.com',
            'mot_de_passe' => 'password123',
            'role' => 'stagiaire',
            'email_verified_at' => now(),
        ]);
        $stagiaire = $user->stagiaire()->create([]);
        $competence = Competence::create(['nom' => 'Laravel']);

        $this->actingAs($user)
            ->get('/stagiaire/competences')
            ->assertOk()
            ->assertSee('Laravel')
            ->assertSee('name="competence_id"', false);

        $this->actingAs($user)
            ->post('/stagiaire/competences', ['competence_id' => $competence->id])
            ->assertRedirect();

        $this->assertDatabaseHas('stagiaire_competence', [
            'stagiaire_id' => $stagiaire->id,
            'competence_id' => $competence->id,
        ]);
    }

    public function test_stagiaire_can_toggle_offer_favorite(): void
    {
        $companyUser = User::create([
            'nom' => 'Martin',
            'prenom' => 'Lea',
            'email' => 'favorite-company@example.com',
            'mot_de_passe' => 'password123',
            'role' => 'entreprise',
            'email_verified_at' => now(),
        ]);
        $entreprise = $companyUser->entreprise()->create([
            'nom_entreprise' => 'Stage Link SARL',
        ]);
        $offre = $entreprise->offres()->create([
            'titre' => 'Stage favori',
            'description' => 'Offre de test',
            'type_stage' => 'PFE',
            'duree' => 3,
            'localisation' => 'Tunis',
            'date_publication' => now()->toDateString(),
            'statut' => 'active',
        ]);
        $stagiaireUser = User::create([
            'nom' => 'Diallo',
            'prenom' => 'Aminata',
            'email' => 'favorite-student@example.com',
            'mot_de_passe' => 'password123',
            'role' => 'stagiaire',
            'email_verified_at' => now(),
        ]);
        $stagiaire = $stagiaireUser->stagiaire()->create([]);

        $this->actingAs($stagiaireUser)
            ->get('/offres/'.$offre->id)
            ->assertOk()
            ->assertSee('Ajouter aux favoris');

        $this->actingAs($stagiaireUser)
            ->post('/stagiaire/offres/'.$offre->id.'/favori')
            ->assertRedirect();
        $this->assertDatabaseHas('favoris', [
            'stagiaire_id' => $stagiaire->id,
            'offre_id' => $offre->id,
        ]);

        $this->actingAs($stagiaireUser)
            ->get('/offres/'.$offre->id)
            ->assertSee('Retirer des favoris');

        $this->actingAs($stagiaireUser)
            ->get('/stagiaire/favoris')
            ->assertOk()
            ->assertSee('Retirer des favoris')
            ->assertSee('Stage Link SARL')
            ->assertSee('/stagiaire/offres/'.$offre->id.'/favori');

        $this->actingAs($stagiaireUser)
            ->post('/stagiaire/offres/'.$offre->id.'/favori')
            ->assertRedirect();
        $this->assertDatabaseMissing('favoris', [
            'stagiaire_id' => $stagiaire->id,
            'offre_id' => $offre->id,
        ]);
    }
}
