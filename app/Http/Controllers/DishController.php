<?php

namespace App\Http\Controllers;

use App\Models\Gerecht;
use App\Models\GerechtCategorie;
use Illuminate\Http\Request;

class DishController extends Controller
{
    public function index()
    {
        $dishes = Gerecht::orderBy('gerecht_categorie')->paginate(10);
        return view('admin.dishes.index', compact('dishes'));
    }

    public function create()
    {
        $categories = GerechtCategorie::all();
        return view('admin.dishes.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'naam' => 'required|string|max:255',
            'prijs' => 'required|numeric',
            'beschrijving' => 'nullable|string',
            'gerecht_categorie' => 'required|string:gerecht_categorieen,naam',
        ]);

        Gerecht::create($data);
        return redirect()->route('admin.dishes.index')->with('success', 'Gerecht toegevoegd.');
    }

    public function show($id)
    {
        $dish = Gerecht::findOrFail($id);
        return view('admin.dishes.show', compact('dish'));
    }

    public function edit($id)
    {
        $categories = GerechtCategorie::all();
        $dish = Gerecht::findOrFail($id);
        return view('admin.dishes.edit', compact('dish', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'naam' => 'required|string|max:255',
            'prijs' => 'required|numeric',
            'beschrijving' => 'nullable|string',
            'gerecht_categorie' => 'required',
        ]);

        $dish = Gerecht::findOrFail($id);
        $dish->update($data);
        return redirect()->route('admin.dishes.index')->with('success', 'Gerecht bijgewerkt.');
    }

    public function destroy($id)
    {
        $dish = Gerecht::findOrFail($id);
        $dish->delete();
        return redirect()->route('admin.dishes.index')->with('success', 'Gerecht verwijderd.');
    }
}