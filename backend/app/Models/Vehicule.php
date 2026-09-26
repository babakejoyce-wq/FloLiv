<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Vehicule extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'immatriculation'];

    public function livreur(): HasOne
    {
        return $this->hasOne(Livreur::class);
    }
}