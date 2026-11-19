<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('stagelink.admin.email');
        $password = config('stagelink.admin.password');

        if (! is_string($email) || filter_var($email, FILTER_VALIDATE_EMAIL) === false
            || ! is_string($password) || strlen($password) < 12) {
            throw new RuntimeException('Set STAGELINK_ADMIN_EMAIL and a password of at least 12 characters before seeding an administrator.');
        }

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'nom' => 'Admin',
                'prenom' => 'Super',
                'mot_de_passe' => Hash::make($password),
                'telephone' => '0600000000',
                'role' => 'admin',
                'statut' => 'actif',
            ]
        );

        if ($user->role !== 'admin') {
            throw new RuntimeException("{$email} already belongs to a non-admin user.");
        }

        Admin::firstOrCreate(
            ['user_id' => $user->id],
            ['niveau_acces' => 'super_admin']
        );
    }
}
