<?php

namespace App\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use CanResetPassword, HasFactory, Notifiable;

    protected $attributes = [
        'statut' => 'actif',
        'auth_session_version' => 0,
    ];

    protected $fillable = [
        'nom', 'prenom', 'email', 'mot_de_passe', 'telephone', 'role', 'statut', 'email_verified_at', 'google_id', 'auth_session_version',
    ];

    protected $hidden = ['mot_de_passe', 'remember_token', 'google_id'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'auth_session_version' => 'integer',
        ];
    }

    // Override the default password field
    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    public function getAuthPasswordName()
    {
        return 'mot_de_passe';
    }

    public function stagiaire()
    {
        return $this->hasOne(Stagiaire::class);
    }

    public function entreprise()
    {
        return $this->hasOne(Entreprise::class);
    }

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    public function emailVerificationOtp(): HasOne
    {
        return $this->hasOne(EmailVerificationOtp::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'utilisateur_id');
    }

    public function unreadNotifications()
    {
        return $this->notifications()->where('lu', false);
    }

    public function isStagiaire()
    {
        return $this->role === 'stagiaire';
    }

    public function isEntreprise()
    {
        return $this->role === 'entreprise';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function getNomCompletAttribute()
    {
        return $this->prenom.' '.$this->nom;
    }
}
