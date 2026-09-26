<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Livreur;
use Illuminate\Http\Request;

class LivreurController extends Controller
{
    public function index()
    {
        return Livreur::with(['zone', 'vehicule'])->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'telephone' => 'required|string|max:20',
            'zone_id' => 'required|exists:zones,id',
            'vehicule_id' => 'required|exists:vehicules,id|unique:livreurs,vehicule_id',
        ]);

        $livreur = Livreur::create($data);

        return response()->json($livreur->load(['zone', 'vehicule']), 201);
    }

    public function show(Livreur $livreur)
    {
        return $livreur->load(['zone', 'vehicule', 'courses']);
    }

    public function update(Request $request, Livreur $livreur)
    {
        $data = $request->validate([
            'nom' => 'sometimes|string|max:100',
            'prenom' => 'sometimes|string|max:100',
            'telephone' => 'sometimes|string|max:20',
            'zone_id' => 'sometimes|exists:zones,id',
            'vehicule_id' => 'sometimes|exists:vehicules,id|unique:livreurs,vehicule_id,' . $livreur->id,
        ]);

        $livreur->update($data);

        return $livreur->load(['zone', 'vehicule']);
    }

    // "Retirer" un livreur = retrait logique (soft), jamais une suppression physique.
    // Traduit RG2 : refus si une course non livrée/annulée lui est encore rattachée.
    public function destroy(Livreur $livreur)
    {
        if (!$livreur->peutEtreRetire()) {
            return response()->json([
                'message' => "Ce livreur a une course en cours et ne peut pas être retiré.",
            ], 422);
        }

        $livreur->update([
            'est_retire' => true,
            'date_retrait' => now(),
        ]);

        return response()->json(['message' => 'Livreur retiré avec succès.']);
    }
}