<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competence extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 'description',
    ];

    public function stagiaires()
    {
        return $this->belongsToMany(Stagiaire::class, 'stagiaire_competence');
    }

    public function offres()
    {
        return $this->belongsToMany(Offre::class, 'offre_competence');
    }
}
