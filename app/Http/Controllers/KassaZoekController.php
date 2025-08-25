<?php

namespace App\Http\Controllers;

use App\Models\Gerecht;
use App\Models\GerechtCategorie;
use Illuminate\Http\Request;

class KassaZoekController extends Controller
{
    public function index(Request $r)
    {
        $q = Gerecht::query()->with('categorie');

        // zoeken op naam of id (gerechtnummer)
        if ($term = trim((string)$r->get('q'))) {
            $q->where(function($qq) use ($term) {
                $qq->where('naam','like',"%{$term}%")
                   ->orWhere('id', $term);
            });
        }

        // filter op categorie
        if ($cat = $r->get('categorie')) {
            $q->where('gerecht_categorie', $cat);
        }

        $resultaten = $q->orderBy('id')->limit(100)->get();
        $cats = GerechtCategorie::orderBy('naam')->get();

        return view('kassa.zoek', compact('resultaten','cats'))
               ->with(['q'=>$r->get('q'),'cat'=>$r->get('categorie')]);
    }
}
