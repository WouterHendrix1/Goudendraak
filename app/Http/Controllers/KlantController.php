<?php

namespace App\Http\Controllers;

use App\Models\Klant;
use App\Models\Tafel;
use Illuminate\Http\Request;

class KlantController extends Controller
{
    public function create($tafelId)
    {
        $tafel = Tafel::findOrFail($tafelId);
        $klanten = $tafel->klanten;

        return view('klanten.create', compact('tafel', 'klanten'));
    }

    public function store(Request $request, $tafelId)
    {
        $request->validate([
            'klanten' => 'required|array|max:8',
            'klanten.*.geboortedatum' => 'required|date',
            'klanten.*.deluxe_menu' => 'nullable|boolean',
        ]);

        $tafel = Tafel::findOrFail($tafelId);

        if ($tafel->klanten()->count() + count($request->klanten) > 8) {
            return back()->withErrors('Maximaal 8 klanten per tafel.');
        }

        foreach ($request->klanten as $klant) {
            if (!empty($klant['geboortedatum'])) {
                $tafel->klanten()->create([
                    'geboortedatum' => $klant['geboortedatum'],
                    'deluxe_menu' => $klant['deluxe_menu'] ?? false,
                ]);
            }
        }

        return redirect()->route('klant.create', $tafelId)->with('success', 'Klanten toegevoegd.');
    }
}
