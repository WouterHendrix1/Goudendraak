<?php

namespace App\Http\Controllers;

use App\Models\Gerecht;
use App\Models\GerechtCategorie;
use Barryvdh\DomPDF\Facade\Pdf;

class MenuController extends Controller {
    public function index()
{
    // Platte dataset voor Vue
    $rows = Gerecht::orderBy('id')
        ->get(['id','naam','prijs','gerecht_categorie'])
        ->map(fn($g) => [
            'id'        => (int) $g->id,
            'naam'      => $g->naam,
            'prijs'     => (float) $g->prijs,
            'categorie' => (string) $g->gerecht_categorie,
        ])
        ->values();

    // Categorienamen voor het filter
    $cats = Gerecht::select('gerecht_categorie')
        ->distinct()
        ->orderBy('gerecht_categorie')
        ->pluck('gerecht_categorie')
        ->filter()   // verwijder null/lege
        ->values();

    return view('menu.index', compact('rows', 'cats'));
}

    public function pdf()
    {
        $groepen = Gerecht::orderBy('gerecht_categorie')
            ->orderBy('naam')
            ->get()
            ->groupBy('gerecht_categorie');

        $logo = $this->dataUri(public_path('images/logo.png')); // null als niet aanwezig

        $pdf = Pdf::setOptions(['dpi' => 300, 'isRemoteEnabled' => true])
            ->loadView('menu.pdf', [
                'groepen' => $groepen,
                'logo'    => $logo,
            ])
            ->setPaper('a4', 'portrait');

        return $pdf->download('menu-'.now()->format('Ymd').'.pdf');
    }

    private function dataUri(?string $path): ?string
    {
        if (!$path || !is_file($path)) return null;
        $mime = mime_content_type($path) ?: 'image/png';
        return 'data:'.$mime.';base64,'.base64_encode(file_get_contents($path));
    }
}
