# StageLink — configuration corrigée

## Installation locale (Windows / XAMPP)
1. Ouvrir phpMyAdmin et créer une base `stagelink` (collation utf8mb4_unicode_ci).
2. Copier `.env.example` vers `.env`.
3. Ajuster `DB_PORT`, `DB_USERNAME` et `DB_PASSWORD` selon VOTRE installation MySQL. Le fichier fourni utilise le port 3306 et `root` sans mot de passe comme valeur de départ uniquement.
4. Dans PowerShell, à la racine du dossier `stagelink`, exécuter :
   ```powershell
   php artisan key:generate
   php artisan config:clear
   php artisan migrate --seed
   php artisan storage:link
   php artisan serve
   ```
5. Visiter http://127.0.0.1:8000

## Important
- Si MySQL refuse `root`, renseigner le bon mot de passe / port dans `.env`; les identifiants ne peuvent pas être devinés ni réparés depuis le code.
- `SESSION_DRIVER=file`, `CACHE_STORE=file`, `QUEUE_CONNECTION=sync` évitent d'exiger des tables de session/cache/queue pendant la configuration locale. L'application nécessite toujours la base `stagelink` pour ses données.
- Ne jamais partager `.env` ni APP_KEY / identifiants. Le `.env` de la machine d'origine a été omis.
- Les notifications de candidature suivent les colonnes réelles de la table (`utilisateur_id`, `type`, `message`, `date_envoi`, `lu`); le champ inexistant `titre` a été retiré.
- Le contrôleur refuse désormais une candidature répétée au même stage.
- Comptes seed de démonstration utilisent le mot de passe `password`; changez-les avant tout déploiement public.
