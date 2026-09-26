<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    // Traduit la fonctionnalité de recherche/filtrage : ?statut=&livreur_id=&debut=&fin=
    public function index(Request $request)
    {
        $query = Course::with('livreur');

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('livreur_id')) {
            $query->where('livreur_id', $request->livreur_id);
        }
        if ($request->filled('debut') && $request->filled('fin')) {
            $query->whereBetween('livree_at', [$request->debut, $request->fin]);
        }

        return $query->get();
    }

    // Une course peut être créée affectée ou non (livreur_id nullable)
    public function store(Request $request)
    {
        $data = $request->validate([
            'livreur_id' => 'nullable|exists:livreurs,id',
            'adresse_depart' => 'required|string|max:255',
            'adresse_arrivee' => 'required|string|max:255',
            'montant' => 'required|numeric|min:0',
        ]);

        if (!empty($data['livreur_id'])) {
            $conflit = $this->verifierConflit($data['livreur_id']);
            if ($conflit && !$request->boolean('force')) {
                return response()->json([
                    'message' => "Ce livreur a déjà une course active. Confirmez avec force=true pour affecter quand même.",
                ], 409);
            }
        }

        $course = Course::create($data + ['statut' => 'en_attente']);

        return response()->json($course->load('livreur'), 201);
    }

    public function show(Course $course)
    {
        return $course->load('livreur');
    }

    // RG3 : le montant ne peut plus être modifié une fois la course livrée
    public function update(Request $request, Course $course)
    {
        if ($course->statut === 'livree' && $request->has('montant')) {
            return response()->json([
                'message' => "Le montant d'une course livrée ne peut plus être modifié.",
            ], 422);
        }

        $data = $request->validate([
            'adresse_depart' => 'sometimes|string|max:255',
            'adresse_arrivee' => 'sometimes|string|max:255',
            'montant' => 'sometimes|numeric|min:0',
        ]);

        $course->update($data);

        return $course->load('livreur');
    }

    // RG1 : progression ordonnée en_attente -> prise_en_charge -> livree
    public function changerStatut(Request $request, Course $course)
    {
        $request->validate(['statut' => 'required|in:prise_en_charge,livree']);

        $ordre = ['en_attente', 'prise_en_charge', 'livree'];
        $positionActuelle = array_search($course->statut, $ordre);
        $positionSouhaitee = array_search($request->statut, $ordre);

        if ($positionActuelle === false || $positionSouhaitee !== $positionActuelle + 1) {
            return response()->json(['message' => 'Transition de statut invalide.'], 422);
        }

        $champDate = $request->statut === 'prise_en_charge' ? 'prise_en_charge_at' : 'livree_at';

        $course->update([
            'statut' => $request->statut,
            $champDate => now(),
        ]);

        return $course->load('livreur');
    }

    // Souhaitable : annulation avec trace (jamais de suppression)
    public function annuler(Request $request, Course $course)
    {
        if ($course->statut === 'livree') {
            return response()->json(['message' => 'Une course livrée ne peut plus être annulée.'], 422);
        }

        $data = $request->validate(['motif_annulation' => 'required|string|max:255']);

        $course->update([
            'statut' => 'annulee',
            'annulee_at' => now(),
            'motif_annulation' => $data['motif_annulation'],
        ]);

        return $course->load('livreur');
    }

    // Souhaitable : réaffectation, limitée aux courses encore "en attente"
    public function affecter(Request $request, Course $course)
    {
        if ($course->statut !== 'en_attente') {
            return response()->json([
                'message' => 'Seule une course en attente peut être (ré)affectée.',
            ], 422);
        }

        $data = $request->validate(['livreur_id' => 'required|exists:livreurs,id']);

        $conflit = $this->verifierConflit($data['livreur_id']);
        if ($conflit && !$request->boolean('force')) {
            return response()->json([
                'message' => "Ce livreur a déjà une course active. Confirmez avec force=true.",
            ], 409);
        }

        $course->update(['livreur_id' => $data['livreur_id']]);

        return $course->load('livreur');
    }

    private function verifierConflit(int $livreurId): bool
    {
        return Course::where('livreur_id', $livreurId)
            ->whereIn('statut', ['en_attente', 'prise_en_charge'])
            ->exists();
    }
}