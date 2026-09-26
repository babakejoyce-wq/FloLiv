<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicule;
use Illuminate\Http\Request;

class VehiculeController extends Controller
{
    public function index()
    {
        return Vehicule::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|string|max:50',
            'immatriculation' => 'nullable|string|max:20',
        ]);
        return response()->json(Vehicule::create($data), 201);
    }

    public function show(Vehicule $vehicule)
    {
        return $vehicule->load('livreur');
    }

    public function update(Request $request, Vehicule $vehicule)
    {
        $data = $request->validate([
            'type' => 'sometimes|string|max:50',
            'immatriculation' => 'nullable|string|max:20',
        ]);
        $vehicule->update($data);
        return $vehicule;
    }

    public function destroy(Vehicule $vehicule)
    {
        if ($vehicule->livreur()->exists()) {
            return response()->json(['message' => 'Véhicule encore utilisé par un livreur.'], 422);
        }
        $vehicule->delete();
        return response()->json(['message' => 'Véhicule supprimé.']);
    }
}