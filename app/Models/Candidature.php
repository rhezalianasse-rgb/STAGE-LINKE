<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidature extends Model
{
    use HasFactory;

    protected $fillable = [
        'stagiaire_id', 'offre_id', 'date_candidature', 'statut', 'lettre_motivation', 'cv_path',
    ];

    protected $casts = [
        'date_candidature' => 'date',
    ];

    public function stagiaire()
    {
        return $this->belongsTo(Stagiaire::class);
    }

    public function offre()
    {
        return $this->belongsTo(Offre::class);
    }
}
