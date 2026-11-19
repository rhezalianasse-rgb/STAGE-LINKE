<?php

namespace Database\Seeders;

use App\Models\Competence;
use Illuminate\Database\Seeder;

class CompetenceSeeder extends Seeder
{
    public function run(): void
    {
        $competences = [
            ['PHP', 'Langage de programmation web côté serveur'],
            ['Laravel', 'Framework PHP pour le développement web'],
            ['JavaScript', 'Langage de programmation côté client'],
            ['React', 'Bibliothèque JavaScript pour les interfaces utilisateur'],
            ['Vue.js', 'Framework JavaScript progressif'],
            ['Python', 'Langage de programmation polyvalent'],
            ['Java', 'Langage de programmation orienté objet'],
            ['HTML/CSS', 'Technologies de base du développement web'],
            ['MySQL', 'Système de gestion de base de données relationnelle'],
            ['Git', 'Système de contrôle de version'],
            ['Docker', 'Plateforme de conteneurisation'],
            ['Node.js', 'Environnement d\'execution JavaScript cote serveur'],
            ['Angular', 'Framework TypeScript pour le développement web'],
            ['Bootstrap', 'Framework CSS pour le design responsive'],
            ['WordPress', 'Système de gestion de contenu'],
            ['Figma', 'Outil de design d\'interface'],
            ['Photoshop', 'Logiciel de retouche d\'image'],
            ['SEO', 'Optimisation pour les moteurs de recherche'],
            ['Marketing Digital', 'Stratégies marketing en ligne'],
            ['Communication', 'Compétences en communication professionnelle'],
        ];

        foreach ($competences as [$nom, $description]) {
            Competence::firstOrCreate(['nom' => $nom], ['description' => $description]);
        }
    }
}
