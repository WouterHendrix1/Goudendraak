<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BestelRegel;
use Carbon\Carbon;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $van = $request->input('van') ?? Carbon::now()->startOfMonth()->toDateString();
        $tot = $request->input('tot') ?? Carbon::now()->endOfMonth()->toDateString();

        $verkopen = BestelRegel::with('gerecht')
            ->whereHas('bestelling', function ($q) use ($van, $tot) {
                $q->whereBetween('created_at', [$van, $tot]);
            })
            ->get()
            ->groupBy('gerecht_id');

        $statistiek = $verkopen->map(function ($regels) {
            $gerecht = $regels->first()->gerecht;
            $totaalAantal = $regels->sum('aantal');
            $totaalOmzet = $regels->sum(function ($regel) {
                return $regel->aantal * $regel->prijs_per_stuk;
            });

            return [
                'gerecht' => $gerecht,
                'aantal' => $totaalAantal,
                'omzet' => $totaalOmzet,
            ];
        });

        return view('admin.sales.index', [
            'statistiek' => $statistiek,
            'van' => $van,
            'tot' => $tot,
        ]);
    }
}
