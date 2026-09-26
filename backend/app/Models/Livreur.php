<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Livreur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 'prenom', 'telephone', 'zone_id', 'vehicule_id',
        'est_retire', 'date_retrait',
    ];

    protected $casts = [
        'est_retire' => 'boolean',
        'date_retrait' => 'datetime',
    ];

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function vehicule(): BelongsTo
    {
        return $this->belongsTo(Vehicule::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    // Traduit RG2 : un livreur ayant une course non livrée/annulée ne peut pas être retiré
    public function peutEtreRetire(): bool
    {
        return !$this->courses()
            ->whereNotIn('statut', ['livree', 'annulee'])
            ->exists();
    }
}