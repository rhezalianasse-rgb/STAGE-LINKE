<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offre extends Model
{
    use HasFactory;

    protected $fillable = [
        'entreprise_id', 'titre', 'description', 'type_stage', 'duree', 'localisation', 'date_publication',
        'date_expiration', 'statut',
    ];

    protected $casts = [
        'date_publication' => 'date',
        'date_expiration' => 'date',
        'duree' => 'integer',
    ];

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function candidatures()
    {
        return $this->hasMany(Candidature::class);
    }

    public function favoris()
    {
        return $this->hasMany(Favori::class);
    }

    public function competences()
    {
        return $this->belongsToMany(Competence::class, 'offre_competence');
    }

    public function scopeActive($query)
    {
        return $query->where('statut', 'active');
    }

    public function scopePubliclyAvailable($query)
    {
        return $query->active()
            ->where(function ($query) {
                $query->whereNull('date_expiration')
                    ->orWhereDate('date_expiration', '>=', today());
            });
    }

    public function isExpired(): bool
    {
        return $this->date_expiration !== null && $this->date_expiration->lt(today());
    }
}
