<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favori extends Model
{
    use HasFactory;

    protected $table = 'favoris';

    protected $fillable = [
        'stagiaire_id', 'offre_id', 'date_ajout',
    ];

    protected $casts = [
        'date_ajout' => 'datetime',
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
