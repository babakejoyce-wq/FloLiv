<?php

namespace App\Exports;

use App\Models\Course;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CoursesExport implements FromCollection, WithHeadings
{
    public function __construct(
        private string $debut,
        private string $fin,
        private ?string $statut = null
    ) {}

    public function collection()
    {
        $query = Course::with('livreur')
            ->whereBetween('created_at', [$this->debut, $this->fin]);

        if ($this->statut) {
            $query->where('statut', $this->statut);
        }

        return $query->get()->map(fn ($c) => [
            'id' => $c->id,
            'livreur' => $c->livreur ? $c->livreur->nom . ' ' . $c->livreur->prenom : 'Non affecté',
            'adresse_depart' => $c->adresse_depart,
            'adresse_arrivee' => $c->adresse_arrivee,
            'montant' => $c->montant,
            'statut' => $c->statut,
            'livree_at' => $c->livree_at?->format('d/m/Y H:i'),
        ]);
    }

    public function headings(): array
    {
        return ['ID', 'Livreur', 'Départ', 'Arrivée', 'Montant', 'Statut', 'Livrée le'];
    }
}