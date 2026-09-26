<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CoursesExport;

class RapportController extends Controller
{
    // RG5 : le CA d'une période ne porte que sur les courses livrées durant cette période
    public function chiffreAffaires(Request $request)
    {
        $request->validate([
            'debut' => 'required|date',
            'fin' => 'required|date|after_or_equal:debut',
            'livreur_id' => 'nullable|exists:livreurs,id',
        ]);

        $query = Course::where('statut', 'livree')
            ->whereBetween('livree_at', [$request->debut, $request->fin]);

        if ($request->filled('livreur_id')) {
            $query->where('livreur_id', $request->livreur_id);
        }

        return response()->json([
            'periode' => ['debut' => $request->debut, 'fin' => $request->fin],
            'nombre_courses' => $query->count(),
            'chiffre_affaires' => $query->sum('montant'),
        ]);
    }

    // Export optionnel : la même liste filtrée, en fichier tableur
    public function export(Request $request)
    {
        $request->validate([
            'debut' => 'required|date',
            'fin' => 'required|date|after_or_equal:debut',
            'statut' => 'nullable|in:en_attente,prise_en_charge,livree,annulee',
        ]);

        return Excel::download(
            new CoursesExport($request->debut, $request->fin, $request->statut),
            'courses_' . $request->debut . '_' . $request->fin . '.xlsx'
        );
    }
}