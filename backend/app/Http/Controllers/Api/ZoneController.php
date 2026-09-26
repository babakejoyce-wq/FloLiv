<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    public function index()
    {
        return Zone::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate(['nom' => 'required|string|max:100']);
        return response()->json(Zone::create($data), 201);
    }

    public function show(Zone $zone)
    {
        return $zone->load('livreurs');
    }

    public function update(Request $request, Zone $zone)
    {
        $data = $request->validate(['nom' => 'required|string|max:100']);
        $zone->update($data);
        return $zone;
    }

    public function destroy(Zone $zone)
    {
        if ($zone->livreurs()->exists()) {
            return response()->json(['message' => 'Zone encore utilisée par des livreurs.'], 422);
        }
        $zone->delete();
        return response()->json(['message' => 'Zone supprimée.']);
    }
}