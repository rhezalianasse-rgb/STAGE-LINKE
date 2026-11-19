<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'nom_entreprise', 'description', 'secteur_activite', 'adresse', 'telephone', 'logo_path', 'site_web',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function offres()
    {
        return $this->hasMany(Offre::class);
    }

    public function specialites()
    {
        return $this->belongsToMany(Specialite::class, 'entreprise_specialite');
    }
}
