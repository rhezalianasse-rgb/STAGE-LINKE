<?php

namespace Database\Seeders;

use App\Models\Competence;
use App\Models\Entreprise;
use Illuminate\Database\Seeder;
use RuntimeException;

class TwentyOffersSeeder extends Seeder
{
    public function run(): void
    {
        $entreprises = Entreprise::query()->orderBy('id')->get();

        if ($entreprises->count() < 3) {
            throw new RuntimeException('At least three companies are required to seed the twenty sample offers.');
        }

        $offers = [
            [
                'titre' => 'Développeur Laravel Junior',
                'description' => 'Participez à la création de fonctionnalités web avec une équipe expérimentée. Missions : développer des écrans et API, corriger des anomalies et rédiger des tests.',
                'type_stage' => 'PFE',
                'duree' => 4,
                'localisation' => 'Casablanca',
                'competences' => ['PHP', 'Laravel', 'MySQL', 'Git'],
            ],
            [
                'titre' => 'Développeur Front-End React',
                'description' => 'Contribuez à des interfaces web accessibles et responsives. Missions : intégrer des maquettes, améliorer les composants et collaborer avec l’équipe produit.',
                'type_stage' => 'PFE',
                'duree' => 4,
                'localisation' => 'Casablanca',
                'competences' => ['JavaScript', 'React', 'HTML/CSS', 'Git'],
            ],
            [
                'titre' => 'Développement d’applications mobiles',
                'description' => 'Découvrez le développement mobile cross-platform. Missions : participer à la réalisation d’écrans, intégrer des API et documenter les fonctionnalités.',
                'type_stage' => 'Stage d\'été',
                'duree' => 2,
                'localisation' => 'Rabat',
                'competences' => ['JavaScript', 'React', 'Node.js'],
            ],
            [
                'titre' => 'Testeur Logiciel et Assurance Qualité',
                'description' => 'Aidez à garantir la qualité de produits numériques. Missions : préparer des scénarios de test, documenter les résultats et suivre les corrections.',
                'type_stage' => 'Stage pré-emploi',
                'duree' => 3,
                'localisation' => 'Casablanca',
                'competences' => ['Java', 'JavaScript', 'Git', 'Communication'],
            ],
            [
                'titre' => 'Assistant DevOps et Intégration Continue',
                'description' => 'Accompagnez l’équipe dans l’amélioration de ses processus de livraison. Missions : documenter les pipelines, participer aux déploiements et suivre les environnements de test.',
                'type_stage' => 'PFE',
                'duree' => 5,
                'localisation' => 'Tanger',
                'competences' => ['Docker', 'Git', 'Node.js'],
            ],
            [
                'titre' => 'Analyste de Données Junior',
                'description' => 'Transformez des données en indicateurs utiles aux équipes. Missions : préparer des jeux de données, réaliser des analyses et présenter les résultats.',
                'type_stage' => 'PFE',
                'duree' => 4,
                'localisation' => 'Casablanca',
                'competences' => ['Python', 'MySQL', 'Communication'],
            ],
            [
                'titre' => 'Développeur Java en Stage',
                'description' => 'Contribuez à des applications métier au sein d’une équipe de développement. Missions : implémenter des évolutions, participer aux revues de code et écrire des tests.',
                'type_stage' => 'Stage pré-emploi',
                'duree' => 6,
                'localisation' => 'Rabat',
                'competences' => ['Java', 'MySQL', 'Git'],
            ],
            [
                'titre' => 'Développeur Web Full Stack Junior',
                'description' => 'Participez à toutes les étapes de réalisation d’un site web. Missions : intégrer des interfaces, contribuer au backend et documenter les changements.',
                'type_stage' => 'Stage d\'observation',
                'duree' => 3,
                'localisation' => 'Fès',
                'competences' => ['PHP', 'JavaScript', 'Bootstrap', 'MySQL'],
            ],
            [
                'titre' => 'Assistant Marketing Digital',
                'description' => 'Participez à la préparation de campagnes digitales. Missions : contribuer au calendrier éditorial, suivre les indicateurs et proposer des améliorations.',
                'type_stage' => 'Stage pré-emploi',
                'duree' => 4,
                'localisation' => 'Rabat',
                'competences' => ['Marketing Digital', 'Communication', 'SEO'],
            ],
            [
                'titre' => 'Designer UI/UX en Stage',
                'description' => 'Aidez à concevoir des expériences numériques claires et cohérentes. Missions : produire des maquettes, organiser les retours utilisateurs et préparer les spécifications.',
                'type_stage' => 'PFE',
                'duree' => 4,
                'localisation' => 'Rabat',
                'competences' => ['Figma', 'Photoshop', 'Communication'],
            ],
            [
                'titre' => 'Assistant SEO et Référencement',
                'description' => 'Contribuez à améliorer la visibilité de sites web. Missions : rechercher des sujets, mettre à jour des contenus et suivre les indicateurs de référencement.',
                'type_stage' => 'Stage d\'été',
                'duree' => 2,
                'localisation' => 'Casablanca',
                'competences' => ['SEO', 'Marketing Digital', 'Communication'],
            ],
            [
                'titre' => 'Community Manager Junior',
                'description' => 'Participez à l’animation de communautés en ligne. Missions : préparer des publications, suivre les interactions et contribuer aux bilans de campagne.',
                'type_stage' => 'Stage d\'observation',
                'duree' => 3,
                'localisation' => 'Marrakech',
                'competences' => ['Marketing Digital', 'Communication', 'Photoshop'],
            ],
            [
                'titre' => 'Assistant Création Graphique',
                'description' => 'Accompagnez l’équipe créative dans la production de supports numériques. Missions : décliner des visuels, organiser les fichiers et respecter les chartes graphiques.',
                'type_stage' => 'Stage d\'été',
                'duree' => 2,
                'localisation' => 'Agadir',
                'competences' => ['Photoshop', 'Figma', 'Communication'],
            ],
            [
                'titre' => 'Chargé d’Analyse Web',
                'description' => 'Aidez à mesurer les performances des contenus et campagnes. Missions : consolider des indicateurs, repérer des tendances et préparer des synthèses.',
                'type_stage' => 'PFE',
                'duree' => 4,
                'localisation' => 'Rabat',
                'competences' => ['Marketing Digital', 'MySQL', 'Communication'],
            ],
            [
                'titre' => 'Assistant Analyste SOC',
                'description' => 'Découvrez les activités de surveillance défensive des systèmes d’information. Missions : contribuer au suivi des alertes, documenter les procédures et préparer des rapports.',
                'type_stage' => 'PFE',
                'duree' => 5,
                'localisation' => 'Marrakech',
                'competences' => ['Python', 'Git', 'Communication'],
            ],
            [
                'titre' => 'Sensibilisation à la Sécurité Informatique',
                'description' => 'Participez à des actions de sensibilisation aux bonnes pratiques numériques. Missions : préparer des supports pédagogiques et contribuer au suivi des ateliers.',
                'type_stage' => 'Stage d\'observation',
                'duree' => 2,
                'localisation' => 'Marrakech',
                'competences' => ['Communication', 'Photoshop', 'Marketing Digital'],
            ],
            [
                'titre' => 'Assistant Systèmes et Réseaux',
                'description' => 'Accompagnez l’équipe dans le suivi des environnements informatiques. Missions : mettre à jour la documentation, suivre les demandes internes et participer aux contrôles de disponibilité.',
                'type_stage' => 'Stage pré-emploi',
                'duree' => 4,
                'localisation' => 'Casablanca',
                'competences' => ['Docker', 'Git', 'Communication'],
            ],
            [
                'titre' => 'Assistant Audit de Sécurité Défensive',
                'description' => 'Participez à des activités encadrées de gouvernance et de prévention. Missions : tenir à jour les listes de contrôle, documenter les recommandations et suivre leur mise en œuvre.',
                'type_stage' => 'PFE',
                'duree' => 4,
                'localisation' => 'Marrakech',
                'competences' => ['Python', 'Git', 'Communication'],
            ],
            [
                'titre' => 'Gestion des Risques Numériques',
                'description' => 'Découvrez les méthodes de gestion des risques liés aux systèmes d’information. Missions : organiser la documentation, contribuer aux tableaux de suivi et préparer des synthèses.',
                'type_stage' => 'Stage pré-emploi',
                'duree' => 6,
                'localisation' => 'Tanger',
                'competences' => ['Communication', 'MySQL', 'Git'],
            ],
            [
                'titre' => 'Support Informatique et Documentation',
                'description' => 'Aidez à améliorer le support aux utilisateurs et la documentation interne. Missions : qualifier les demandes, rédiger des guides et suivre les résolutions.',
                'type_stage' => 'Stage d\'été',
                'duree' => 2,
                'localisation' => 'Fès',
                'competences' => ['Communication', 'Git'],
            ],
        ];

        $created = 0;

        foreach ($offers as $index => $offerData) {
            $competenceNames = $offerData['competences'];
            unset($offerData['competences']);

            $offerData['date_publication'] = today();
            $offerData['date_expiration'] = today()->addDays(30 + ($index % 5) * 7);
            $offerData['statut'] = 'active';

            $entreprise = $entreprises[$index < 8 ? 0 : ($index < 14 ? 1 : 2)];
            $offre = $entreprise->offres()->firstOrCreate(
                ['titre' => $offerData['titre']],
                $offerData
            );

            $competenceIds = Competence::query()
                ->whereIn('nom', $competenceNames)
                ->pluck('id')
                ->all();
            $offre->competences()->syncWithoutDetaching($competenceIds);

            if ($offre->wasRecentlyCreated) {
                $created++;
            }
        }

        $this->command?->info("Offres de démonstration ajoutées : {$created}");
    }
}
