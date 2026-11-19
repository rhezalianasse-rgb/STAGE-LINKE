<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stagiaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'niveau_etude', 'domaine', 'cv_path', 'lettre_motivation', 'date_naissance', 'adresse', 'photo_path',
    ];

    protected $casts = [
        'date_naissance' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function competences()
    {
        return $this->belongsToMany(Competence::class, 'stagiaire_competence');
    }

    public function candidatures()
    {
        return $this->hasMany(Candidature::class);
    }

    public function favoris()
    {
        return $this->hasMany(Favori::class);
    }
}
