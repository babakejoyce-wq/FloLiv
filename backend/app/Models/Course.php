<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'livreur_id', 'adresse_depart', 'adresse_arrivee', 'montant',
        'statut', 'prise_en_charge_at', 'livree_at', 'annulee_at', 'motif_annulation',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'prise_en_charge_at' => 'datetime',
        'livree_at' => 'datetime',
        'annulee_at' => 'datetime',
    ];

    public function livreur(): BelongsTo
    {
        return $this->belongsTo(Livreur::class);
    }
}