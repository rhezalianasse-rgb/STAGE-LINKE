# StageLink

StageLink est une plateforme de recherche de stages qui met en relation les stagiaires et les entreprises au Maroc. Les stagiaires peuvent créer un profil, rechercher des offres, enregistrer des favoris et suivre leurs candidatures. Les entreprises publient des offres et gèrent les candidatures reçues. L'administration gère les comptes, les offres, les compétences et les spécialités.

## Technologies

- PHP 8.2+
- Laravel 12
- MySQL ou SQLite
- Node.js et Vite
- Bootstrap 5, avec les feuilles de style applicatives compilées par Vite

## Prérequis

- PHP 8.2+ avec les extensions Laravel/PDO correspondant à votre base
- Composer
- Node.js et npm
- MySQL/MariaDB, ou SQLite pour le développement local

## Installation locale

Depuis la racine du projet :

```powershell
composer install
if (-not (Test-Path .env)) { Copy-Item .env.example .env }
php artisan key:generate
npm install
```

Configurez ensuite `.env` avant toute migration. Ne remplacez pas un `.env` existant et ne partagez jamais son contenu.

### MySQL/MariaDB

Créez une base `stagelink`, puis réglez `DB_CONNECTION=mysql`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME` et `DB_PASSWORD` selon votre installation. Les valeurs d'exemple (`root` sans mot de passe) ne sont pas universelles.

### SQLite (développement local)

Définissez `DB_CONNECTION=sqlite` et `DB_DATABASE=database/database.sqlite`. Créez le fichier s'il n'existe pas :

```powershell
if (-not (Test-Path database\database.sqlite)) { New-Item database\database.sqlite -ItemType File }
```

### Migrer et lancer

```powershell
php artisan migrate
php artisan storage:link
npm run build
```

Pour le développement avec rechargement Vite, ouvrez deux terminaux :

```powershell
php artisan serve
npm run dev
```

Puis visitez <http://127.0.0.1:8000>.

Les migrations sont additives. N'utilisez pas `migrate:fresh` sur une base qui contient des données.

## Compte administrateur

Pour créer un administrateur dédié, configurez `STAGELINK_ADMIN_EMAIL` et `STAGELINK_ADMIN_PASSWORD` dans l'environnement, puis exécutez :

```powershell
php artisan db:seed --class=AdminSeeder
```

Choisissez un mot de passe unique et robuste; ne mettez pas de secret dans le dépôt. Le `DatabaseSeeder` est destiné aux démonstrations locales et crée des comptes de démonstration; ne l'exécutez pas en production.

## Fonctionnalités

- Inscription stagiaire et connexion par rôle
- Profils stagiaires et entreprises, compétences et spécialités
- Recherche d'offres par mot-clé, localisation, durée, type et domaine
- Création, modification et suivi des dates limites des offres
- Candidatures avec CV stockés dans l'espace privé
- Téléchargement de CV réservé au stagiaire, à l'entreprise liée à la candidature et à l'administrateur
- Favoris et notifications privées
- Réinitialisation de mot de passe par e-mail
- Vérification de l'adresse e-mail par code à usage unique et connexion Google facultative
- Administration des utilisateurs, offres, candidatures, compétences et spécialités

Si des CV historiques existent dans `storage/app/public/cvs`, exécutez `php artisan stagelink:protect-cvs` après avoir sauvegardé les fichiers et configuré la bonne base. La commande copie et vérifie chaque fichier dans le stockage privé avant de retirer sa copie publique.

## Configuration des e-mails et de Google

Les nouveaux comptes créés par mot de passe doivent vérifier leur adresse avec le code envoyé par e-mail. Les comptes existants sont conservés comme vérifiés par la migration. Les inscriptions Google sont réservées aux stagiaires et exigent une adresse confirmée par Google; un compte Stage Link existant n'est jamais lié automatiquement.

### Gmail SMTP

Dans le compte Google utilisé pour l'envoi, activez la validation en deux étapes et créez un mot de passe d'application. Renseignez ensuite les variables suivantes dans le fichier `.env` local, sans partager ni committer ces valeurs :

```dotenv
MAIL_MAILER=smtp
MAIL_SCHEME=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
GMAIL_USERNAME=adresse@gmail.com
GMAIL_APP_PASSWORD=mot_de_passe_application
```

Le code de vérification expire après cinq minutes. Le pilote de messagerie `log` est volontairement refusé pour les codes et les liens de réinitialisation afin de ne pas écrire ces secrets dans les journaux.
Le transport SMTP tente STARTTLS automatiquement et refuse l'envoi si TLS ne peut pas être négocié.

### Connexion Google OAuth

Dans Google Cloud Console, créez un identifiant OAuth de type « Application Web ». Ajoutez comme URI de redirection autorisée la valeur exacte de `GOOGLE_REDIRECT_URI` (par défaut `APP_URL/auth/google/callback`), puis renseignez les identifiants dans `.env` :

```dotenv
GOOGLE_CLIENT_ID=identifiant_client
GOOGLE_CLIENT_SECRET=secret_client
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
```

Après toute modification de configuration, videz le cache local avant de tester :

```powershell
php artisan config:clear
php artisan migrate
```

Pour lier Google à un compte existant, connectez-vous d'abord puis utilisez l'action « Lier Google » dans le menu ou le profil. Le flux de liaison exige une session authentifiée et un retour OAuth valide.

## Tests et commandes utiles

```powershell
php artisan test
npm run build
php artisan route:list
php artisan migrate:status
```

Configurez un transport SMTP valide pour la réinitialisation de mot de passe en production. En production, définissez `APP_ENV=production`, `APP_DEBUG=false`, une `APP_KEY` protégée, HTTPS et des identifiants de base de données propres à l'environnement. Les valeurs locales de `.env.example` ne constituent pas une configuration de production.

## Architecture

- `app/Http/Controllers` : authentification, rôles, offres, candidatures, profils et administration
- `app/Http/Middleware` : contrôle d'accès et rôles
- `app/Models` : modèles Eloquent et relations
- `database/migrations`, `database/seeders`, `database/factories` : schéma, données de démonstration et données de test
- `routes/web.php` : routes HTTP
- `resources/views` : interfaces Blade
- `resources/css`, `resources/js`, `vite.config.js` : ressources frontend
- `storage/app/private` : documents privés, dont les CV
- `storage/app/public` : ressources publiques, dont les logos et photos de profil
