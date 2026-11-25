<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Memoire extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'resume',
        'mots_cles',
        'encadreur',
        'fichier_pdf',
        'statut',
        'motif_rejet',
        'telechargement_autorise',
        'acces_restreint',
        'date_embargo',
        'score_similarite',
        'etudiant_id',
        'filiere_id',
        'annee_academique_id',
    ];

    protected function casts(): array
    {
        return [
            'telechargement_autorise' => 'boolean',
            'acces_restreint' => 'boolean',
            'date_embargo' => 'date',
            'score_similarite' => 'decimal:2',
        ];
    }

    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'etudiant_id');
    }

    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere::class);
    }

    public function annee(): BelongsTo
    {
        return $this->belongsTo(AnneeAcademique::class, 'annee_academique_id');
    }

    public function anneeAcademique(): BelongsTo
    {
        return $this->belongsTo(AnneeAcademique::class, 'annee_academique_id');
    }

    public function validations(): HasMany
    {
        return $this->hasMany(Validation::class);
    }

    public function consultations(): HasMany
    {
        return $this->hasMany(Consultation::class);
    }

    public function favorisPar(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favoris')->withTimestamps();
    }

    // Logique métier
    public function scopeVisibles($query)
    {
        return $query->where('statut', \App\Enums\StatutMemoire::Valide->value)
            ->where(function ($q) {
                $q->whereNull('date_embargo')
                  ->orWhere('date_embargo', '<=', now());
            });
    }

    public function estPublie(): bool
    {
        $statut = $this->statut instanceof \BackedEnum ? $this->statut->value : (string) $this->statut;

        return $statut === \App\Enums\StatutMemoire::Valide->value
            && ($this->date_embargo === null || \Illuminate\Support\Carbon::parse($this->date_embargo)->lte(now()));
    }
}