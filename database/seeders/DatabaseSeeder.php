<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Candidature;
use App\Models\Entreprise;
use App\Models\Notification;
use App\Models\Offre;
use App\Models\Specialite;
use App\Models\Stagiaire;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            throw new RuntimeException('DatabaseSeeder contains demonstration accounts and cannot run in production.');
        }

        $this->call(CompetenceSeeder::class);

        // ========================================
        // Spécialités
        // ========================================
        $specialites = [
            ['nom' => 'Développement Web', 'description' => 'Création de sites et applications web'],
            ['nom' => 'Développement Mobile', 'description' => 'Création d\'applications mobiles'],
            ['nom' => 'Data Science', 'description' => 'Analyse et science des données'],
            ['nom' => 'Cybersécurité', 'description' => 'Sécurité informatique et protection des données'],
            ['nom' => 'Design UI/UX', 'description' => 'Conception d\'interfaces utilisateur'],
            ['nom' => 'Marketing Digital', 'description' => 'Marketing et communication numérique'],
            ['nom' => 'Intelligence Artificielle', 'description' => 'IA et apprentissage automatique'],
            ['nom' => 'DevOps', 'description' => 'Intégration et déploiement continus'],
            ['nom' => 'Réseaux & Systèmes', 'description' => 'Administration réseau et systèmes'],
            ['nom' => 'Gestion de Projet', 'description' => 'Management et pilotage de projets IT'],
        ];

        foreach ($specialites as $spec) {
            Specialite::create($spec);
        }

        // ========================================
        // Admin User
        // ========================================
        $adminUser = User::create([
            'nom' => 'Admin',
            'prenom' => 'Super',
            'email' => 'admin@stagelink.com',
            'mot_de_passe' => Hash::make('password'),
            'telephone' => '0600000000',
            'role' => 'admin',
            'date_inscription' => now(),
            'statut' => 'actif',
        ]);

        Admin::create([
            'user_id' => $adminUser->id,
            'niveau_acces' => 'super_admin',
        ]);

        // ========================================
        // Entreprises
        // ========================================
        $entreprisesData = [
            [
                'user' => ['nom' => 'Alaoui', 'prenom' => 'Mohamed', 'email' => 'contact@techma.ma', 'telephone' => '0522334455'],
                'entreprise' => ['nom_entreprise' => 'TechMa Solutions', 'description' => 'Entreprise spécialisée dans le développement de solutions digitales innovantes.', 'secteur_activite' => 'Technologies de l\'information', 'adresse' => 'Casablanca, Maroc', 'telephone' => '0522334455', 'site_web' => 'https://techma.ma'],
                'specialites' => [1, 2, 7],
            ],
            [
                'user' => ['nom' => 'Benani', 'prenom' => 'Sara', 'email' => 'rh@digitalwave.ma', 'telephone' => '0537112233'],
                'entreprise' => ['nom_entreprise' => 'Digital Wave Agency', 'description' => 'Agence de marketing digital et création de contenu.', 'secteur_activite' => 'Marketing & Communication', 'adresse' => 'Rabat, Maroc', 'telephone' => '0537112233', 'site_web' => 'https://digitalwave.ma'],
                'specialites' => [5, 6],
            ],
            [
                'user' => ['nom' => 'Chakir', 'prenom' => 'Youssef', 'email' => 'contact@cyberfort.ma', 'telephone' => '0528445566'],
                'entreprise' => ['nom_entreprise' => 'CyberFort Security', 'description' => 'Cabinet de conseil en cybersécurité et protection des systèmes d\'information.', 'secteur_activite' => 'Cybersécurité', 'adresse' => 'Marrakech, Maroc', 'telephone' => '0528445566', 'site_web' => 'https://cyberfort.ma'],
                'specialites' => [4, 9],
            ],
        ];

        $entreprises = [];
        foreach ($entreprisesData as $data) {
            $user = User::create(array_merge($data['user'], [
                'mot_de_passe' => Hash::make('password'),
                'role' => 'entreprise',
                'date_inscription' => now(),
                'statut' => 'actif',
            ]));

            $entreprise = Entreprise::create(array_merge($data['entreprise'], [
                'user_id' => $user->id,
            ]));

            $entreprise->specialites()->attach($data['specialites']);
            $entreprises[] = $entreprise;
        }

        // ========================================
        // Offres de Stage
        // ========================================
        $offresData = [
            [
                'entreprise_id' => $entreprises[0]->id,
                'titre' => 'Développeur Web Full Stack - Stage PFE',
                'description' => "Nous recherchons un stagiaire motivé pour rejoindre notre équipe de développement web. Le stagiaire participera au développement d'applications web en utilisant les technologies modernes (Laravel, Vue.js).\n\nMissions :\n- Développement de fonctionnalités backend et frontend\n- Participation aux code reviews\n- Tests et débogage\n- Documentation technique",
                'type_stage' => 'PFE',
                'duree' => 4,
                'localisation' => 'Casablanca',
                'date_publication' => Carbon::now()->subDays(5),
                'statut' => 'active',
                'competences' => [1, 2, 3, 5, 8, 9],
            ],
            [
                'entreprise_id' => $entreprises[0]->id,
                'titre' => 'Développeur Mobile React Native',
                'description' => "Stage de développement d'applications mobiles cross-platform avec React Native.\n\nMissions :\n- Développement d'applications mobiles\n- Intégration d'APIs REST\n- Tests sur iOS et Android",
                'type_stage' => 'Stage d\'été',
                'duree' => 2,
                'localisation' => 'Casablanca',
                'date_publication' => Carbon::now()->subDays(3),
                'statut' => 'active',
                'competences' => [3, 4, 10],
            ],
            [
                'entreprise_id' => $entreprises[1]->id,
                'titre' => 'Chargé(e) de Marketing Digital',
                'description' => "Rejoignez notre équipe marketing pour un stage enrichissant.\n\nMissions :\n- Gestion des réseaux sociaux\n- Création de contenu digital\n- Campagnes publicitaires en ligne\n- Analyse des performances",
                'type_stage' => 'Stage pré-emploi',
                'duree' => 6,
                'localisation' => 'Rabat',
                'date_publication' => Carbon::now()->subDays(10),
                'statut' => 'active',
                'competences' => [18, 19, 20],
            ],
            [
                'entreprise_id' => $entreprises[1]->id,
                'titre' => 'UI/UX Designer - Stage',
                'description' => "Stage en design d'interfaces utilisateur.\n\nMissions :\n- Création de maquettes et prototypes\n- Design responsive\n- Tests utilisateurs\n- Collaboration avec l'équipe développement",
                'type_stage' => 'PFE',
                'duree' => 4,
                'localisation' => 'Rabat',
                'date_publication' => Carbon::now()->subDays(7),
                'statut' => 'active',
                'competences' => [8, 14, 16, 17],
            ],
            [
                'entreprise_id' => $entreprises[2]->id,
                'titre' => 'Analyste Cybersécurité Junior',
                'description' => "Stage en cybersécurité au sein de notre SOC.\n\nMissions :\n- Surveillance des systèmes\n- Analyse de vulnérabilités\n- Rédaction de rapports de sécurité\n- Veille technologique",
                'type_stage' => 'Stage d\'observation',
                'duree' => 3,
                'localisation' => 'Marrakech',
                'date_publication' => Carbon::now()->subDays(2),
                'statut' => 'active',
                'competences' => [6, 9, 10, 11],
            ],
        ];

        foreach ($offresData as $data) {
            $competenceIds = $data['competences'];
            unset($data['competences']);
            $offre = Offre::create($data);
            $offre->competences()->attach($competenceIds);
        }

        // ========================================
        // Stagiaires
        // ========================================
        $stagiairesData = [
            [
                'user' => ['nom' => 'El Amrani', 'prenom' => 'Fatima', 'email' => 'fatima@email.com', 'telephone' => '0611223344'],
                'stagiaire' => ['niveau_etude' => 'Bac+5', 'domaine' => 'Informatique', 'date_naissance' => '2000-05-15', 'adresse' => 'Casablanca, Maroc'],
                'competences' => [1, 2, 3, 8, 9, 10],
            ],
            [
                'user' => ['nom' => 'Fassi', 'prenom' => 'Ahmed', 'email' => 'ahmed@email.com', 'telephone' => '0622334455'],
                'stagiaire' => ['niveau_etude' => 'Bac+3', 'domaine' => 'Marketing Digital', 'date_naissance' => '2001-08-22', 'adresse' => 'Rabat, Maroc'],
                'competences' => [8, 14, 18, 19, 20],
            ],
            [
                'user' => ['nom' => 'Tazi', 'prenom' => 'Khadija', 'email' => 'khadija@email.com', 'telephone' => '0633445566'],
                'stagiaire' => ['niveau_etude' => 'Bac+4', 'domaine' => 'Génie Logiciel', 'date_naissance' => '1999-12-03', 'adresse' => 'Fès, Maroc'],
                'competences' => [1, 3, 5, 6, 7, 9, 10, 11],
            ],
        ];

        $stagiaires = [];
        foreach ($stagiairesData as $data) {
            $user = User::create(array_merge($data['user'], [
                'mot_de_passe' => Hash::make('password'),
                'role' => 'stagiaire',
                'date_inscription' => now(),
                'statut' => 'actif',
            ]));

            $stagiaire = Stagiaire::create(array_merge($data['stagiaire'], [
                'user_id' => $user->id,
            ]));

            $stagiaire->competences()->attach($data['competences']);
            $stagiaires[] = $stagiaire;
        }

        // ========================================
        // Candidatures
        // ========================================
        Candidature::create([
            'stagiaire_id' => $stagiaires[0]->id,
            'offre_id' => 1,
            'date_candidature' => Carbon::now()->subDays(4),
            'statut' => 'en_attente',
            'lettre_motivation' => 'Je suis très motivée par ce stage en développement web...',
        ]);

        Candidature::create([
            'stagiaire_id' => $stagiaires[1]->id,
            'offre_id' => 3,
            'date_candidature' => Carbon::now()->subDays(8),
            'statut' => 'acceptee',
            'lettre_motivation' => 'Le marketing digital est ma passion...',
        ]);

        Candidature::create([
            'stagiaire_id' => $stagiaires[2]->id,
            'offre_id' => 5,
            'date_candidature' => Carbon::now()->subDays(1),
            'statut' => 'en_attente',
            'lettre_motivation' => 'La cybersécurité m\'intéresse énormément...',
        ]);

        // ========================================
        // Notifications
        // ========================================
        Notification::create([
            'utilisateur_id' => $stagiaires[1]->user_id,
            'type' => 'candidature',
            'message' => 'Votre candidature pour le poste "Chargé(e) de Marketing Digital" a été acceptée !',
            'date_envoi' => Carbon::now()->subDays(6),
            'lu' => false,
        ]);

        Notification::create([
            'utilisateur_id' => $entreprises[0]->user_id,
            'type' => 'candidature',
            'message' => 'Nouvelle candidature reçue de Fatima El Amrani pour "Développeur Web Full Stack - Stage PFE".',
            'date_envoi' => Carbon::now()->subDays(4),
            'lu' => false,
        ]);

        echo "Base de données peuplée avec succès !\n";
        echo "Comptes de test :\n";
        echo "Admin: admin@stagelink.com / password\n";
        echo "Entreprise: contact@techma.ma / password\n";
        echo "Stagiaire: fatima@email.com / password\n";
    }
}
