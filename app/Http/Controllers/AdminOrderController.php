<?php

namespace App\Http\Controllers;

use App\Models\Bestelling;
use App\Models\BestelRegel;
use App\Models\Tafel;
use App\Models\Gerecht;
use App\Models\Klant;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
    public function index()
    {
        $bestellingen = Bestelling::with('regels.gerecht')
            ->orderBy('created_at','desc')
            ->paginate(10);

        return view('admin.orders.index', compact('bestellingen'));
    }

    public function create()
    {
        $gerechten = Gerecht::all();

        $tafels = Tafel::whereDoesntHave('bestellingen', fn($q) =>
            $q->where('status', '!=', 'betaald')
        )->get();

        // top 20 meest gebruikte opmerkingen uit eerdere regels
        $opmerkingSuggesties = BestelRegel::whereNotNull('opmerking')
            ->where('opmerking', '!=', '')
            ->groupBy('opmerking')
            ->orderByDesc(DB::raw('COUNT(*)'))
            ->limit(20)
            ->pluck('opmerking');

        return view('admin.orders.create', compact('gerechten', 'tafels', 'opmerkingSuggesties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.gerecht_id' => 'required|exists:gerecht,id',
            'items.*.aantal' => 'required|integer|min:0',
            'tafel_id' => 'nullable|exists:tafels,id',
            'items.*.opmerking' => 'nullable|string|max:255',
        ]);

        $hasGerecht = false;
        foreach ($request->items as $item) {
            if ($item['aantal'] > 0) {
                $hasGerecht = true;
                break;
            }
        }
        if (!$hasGerecht) {
            return redirect()
                ->back()
                ->withErrors(['items' => 'Je moet minstens één gerecht bestellen.'])
                ->withInput();
        }

        if($request->tafel_id){
            $klanten = $request->input('klanten');
            $hasGeboorteDatum = false;
            foreach($klanten as $klant){
                if($klant['geboortedatum']){
                    $hasGeboorteDatum = true;
                    break;
                }
            }
            if(!$hasGeboorteDatum){
                return redirect()
                ->back()
                ->withErrors(['klanten.0.geboortedatum' => 'Er moet minstens één geboortedatum opgegeven worden als je een tafel selecteert.']);
            }
        }

        // Maak een nieuwe bestelling aan
        $order = Bestelling::create([
            'totaalprijs' => 0,
            'tafel_id' => $request->input('tafel_id')
        ]);

        //maak klanten aan
        if($request->input('klanten')) {
            foreach($request->input('klanten') as $klant) {
                if($klant['geboortedatum']){
                    Klant::create([
                        'tafel_id' => $request->input('tafel_id'),
                        'bestelling_id' => $order->id,
                        'geboortedatum' => $klant['geboortedatum'],
                        'deluxe_menu' => $klant['deluxe_menu'],
                    ]);
                }
            }
        }

        $totaal = 0;

        foreach ($request->items as $item) {
        if ($item['aantal'] > 0) {
            $gerecht = Gerecht::find($item['gerecht_id']);
            $regelTotaal = $gerecht->prijs * $item['aantal'];
            $totaal += $regelTotaal;

            $order->regels()->create([
                'gerecht_id'     => $gerecht->id,
                'aantal'         => $item['aantal'],
                'prijs_per_stuk' => $gerecht->prijs,
                'opmerking'      => $item['opmerking'] ?? null,
            ]);
        }
    }
        
        $order->update(['totaal' => $totaal]);

        return redirect()->route('admin.orders.index')
                        ->with('success', 'Bestelling succesvol opgeslagen!');
    }

    public function show($id)
    {
        $bestelling = Bestelling::with('regels.gerecht')->findOrFail($id);
        return view('admin.orders.show', compact('bestelling'));
    }

    public function edit($id)
    {
        $bestelling = Bestelling::with('regels.gerecht')->findOrFail($id);
        $klanten = null;
        $hasdelen = false;
        if($bestelling->tafel_id){
            $klanten = Klant::where('bestelling_id', $bestelling->id)->get();
            $hasdelen = $klanten->contains(function ($klant) {
                return $klant->delen->isNotEmpty();
            });
        }
        if($hasdelen){
            //calculate price per customer based on the delen en gerechten
            $klanten->each(function ($klant) use ($bestelling) {
                $prijs = 0;
                foreach($klant->delen as $deel){
                    foreach($deel->items as $item){
                        $regel = $bestelling->regels->firstWhere('id', $item->bestel_regel_id);
                        if($regel){
                            $prijs += $item->aantal * $regel->prijs_per_stuk;
                        }
                    }
                }
                $klant->prijs_per_gast = $prijs;
            });
        }

        return view('admin.orders.edit', compact('bestelling', 'klanten'));
    }

    public function update(Request $request, $id)
    {
        $bestelling = Bestelling::findOrFail($id);

        // voorbeeld: alleen status aanpassen
        $bestelling->status = $request->input('status');
        $bestelling->save();

        return redirect()->route('admin.orders.index')->with('success','Bestelling bijgewerkt!');
    }

    public function destroy($id)
    {
        $bestelling = Bestelling::findOrFail($id);
        $bestelling->delete();

        return redirect()->route('admin.orders.index')->with('success','Bestelling verwijderd!');
    }

    public function delen($id)
    {
        $bestelling = Bestelling::with([
            'regels.gerecht',
            'klanten',
            'delen.klant',
            'delen.items.regel'
        ])->findOrFail($id);

        // Zorg dat voor elke klant er (optioneel) alvast een deel kan bestaan (niet verplicht aanmaken)
        $delenPerKlant = $bestelling->delen->keyBy('klant_id');
        // Map: regel_id => [klant_id => aantal]
        $huidigeVerdeling = [];
        foreach ($bestelling->delen as $deel) {
            foreach ($deel->items as $pi) {
                $huidigeVerdeling[$pi->bestel_regel_id][$deel->klant_id] = $pi->aantal;
            }
        }
    

        return view('admin.orders.delen_klanten', compact('bestelling', 'delenPerKlant', 'huidigeVerdeling'));
    }

    public function storeDelen(Request $request, $id)
    {
        $bestelling = Bestelling::with(['regels', 'klanten', 'delen'])->findOrFail($id);
        $request->validate([
            'items' => 'nullable|array',
            'items.*.regel_id' => 'required|exists:bestel_regels,id',
            'items.*.klanten' => 'nullable|array',
            'items.*.klanten.*' => 'nullable|integer|min:0',
        ]);

        // Cache: klant_id => BestellingsDeel
        $deelCache = $bestelling->delen->keyBy('klant_id');

        foreach ($request->input('items', []) as $row) {
            $regelId = (int) $row['regel_id'];
            $regel   = $bestelling->regels->firstWhere('id', $regelId);
            if (!$regel) continue;

            $splits = $row['klanten'] ?? [];

            // Validatie: som per regel mag niet groter dan aantal
            $som = array_sum(array_map('intval', $splits));
            if ($som > $regel->aantal) {
                return back()->withErrors([
                    'items' => "Verdeling groter dan totaal voor gerecht {$regel->gerecht->naam}."
                ])->withInput();
            }
            if($som < $regel->aantal){
                return back()->withErrors([
                    'items' => "Verdeling kleiner dan totaal voor gerecht {$regel->gerecht->naam}."
                ])->withInput();
            }

            foreach ($splits as $klantId => $aantal) {
                $aantal = (int) $aantal;
                if ($aantal < 0) $aantal = 0;

                if ($aantal === 0) {
                    // Als er eerder iets stond, mag je het ook verwijderen (optioneel)
                    if (isset($deelCache[$klantId])) {
                        $deelCache[$klantId]->items()
                            ->where('bestel_regel_id', $regelId)
                            ->delete();
                    }
                    continue;
                }

                // Deel voor deze klant aanmaken indien nodig
                if (!isset($deelCache[$klantId])) {
                    $klant = $bestelling->klanten->firstWhere('id', (int)$klantId);
                    if (!$klant) continue;

                    $deelCache[$klantId] = $bestelling->delen()->create([
                        'index'   => $deelCache->count() + 1,
                        'naam'    => null,
                        'klant_id'=> $klant->id,
                    ]);
                }

                // Upsert koppeling (1 regel per klant)
                $pi = $deelCache[$klantId]->items()->firstOrNew([
                    'bestel_regel_id' => $regel->id,
                ]);
                $pi->aantal = $aantal;
                $pi->save();
            }
        }

        return redirect()->route('admin.orders.delen', $bestelling->id)
            ->with('success', 'Verdeling per klant opgeslagen.');
    }

    public function pdf($id)
    {
        $bestelling = Bestelling::with(['regels.gerecht','tafel','klanten'])->findOrFail($id);

        // Logo in base64 (public/images/logo.png)
        $logo = $this->dataUriIfExists(public_path('images/logo.png'));

        // Item-afbeelding (optioneel): kolom 'afbeelding' op Gerecht of placeholder
        $items = $bestelling->regels->map(function($r){
            $gerecht = $r->gerecht;

            return [
                'id'      => $gerecht->id,
                'naam'    => $gerecht->naam,
                'aantal'  => (int)$r->aantal,
                'prijs'   => (float)$r->prijs_per_stuk,
                'totaal'  => (float)$r->aantal * (float)$r->prijs_per_stuk,
            ];
        });

        $totaal = $items->sum('totaal');

        // 8.5 cm × 10 cm in punten (1 cm = 28.3465 pt)
        $w = 8.5 * 28.3465;   // ≈ 241.95
        $h = 10  * 28.3465;   // ≈ 283.47

        $pdf = Pdf::loadView('admin.orders.pdf', [
            'bestelling' => $bestelling,
            'items'      => $items,
            'totaal'     => $totaal,
            'logo'       => $logo,
        ])->setPaper([0, 0, $w, $h], 'portrait');

        // Download of stream:
        return $pdf->download('bon-'.$bestelling->id.'.pdf');
        // return $pdf->stream('bon-'.$bestelling->id.'.pdf');
    }

    private function dataUriIfExists($pathOrUrl): ?string
    {
        try {
            if (!$pathOrUrl) return null;

            // URLs or data URIs: return as-is
            if (\Illuminate\Support\Str::startsWith($pathOrUrl, ['http://','https://','data:'])) {
                return $pathOrUrl;
            }

            // If not absolute (no drive/UNC/leading slash), make it relative to /public
            if (!preg_match('/^(?:[a-zA-Z]:\\\\|\\\\\\\\|\/)/', $pathOrUrl)) {
                $pathOrUrl = public_path($pathOrUrl);
            }

            if (!is_file($pathOrUrl) || !is_readable($pathOrUrl)) return null;

            $ext  = strtolower(pathinfo($pathOrUrl, PATHINFO_EXTENSION));
            $mime = $ext === 'svg' ? 'image/svg+xml' : (mime_content_type($pathOrUrl) ?: 'image/png');
            $data = base64_encode(file_get_contents($pathOrUrl));
            return "data:$mime;base64,$data";
        } catch (\Throwable $e) {
            \Log::warning('dataUriIfExists failed: '.$e->getMessage());
            return null;
        }
    }
}
