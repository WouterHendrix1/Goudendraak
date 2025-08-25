@extends('layouts.app')
@section('title','Nieuwe bestelling')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/orders.css') }}">
@endpush

@section('content')
<div class="container-lg">

  <div class="tablet-toolbar mb-3">
    <h1>Nieuwe bestelling</h1>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">⬅ Terug naar bestellingen</a>
  </div>

  @if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
  @endif

  <form method="POST" action="{{ route('admin.orders.store') }}">
    @csrf

    {{-- Tafelselectie --}}
    <div class="section-card mb-4">
      <div class="form-group mb-0">
        <label for="tafel_id" class="form-label fw-semibold">Selecteer Tafel (optioneel)</label>
        <select name="tafel_id" id="tafel_id" class="form-control" onchange="toggleKlantenSection()">
          <option value="">-- Geen tafel (afhaal) --</option>
          @foreach($tafels as $tafel)
            <option value="{{ $tafel->id }}" {{ old('tafel_id') == $tafel->id ? 'selected' : '' }}>
              Tafel {{ $tafel->id }}
            </option>
          @endforeach
        </select>
      </div>
    </div>

        {{-- Klanten toevoegen (dynamisch) --}}
    <div id="klanten-root"
        data-old='@json(old("klanten", []))'
        data-tafel="{{ old('tafel_id') }}">

      <div class="section-card mb-4" v-show="visible">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <h4 class="mb-0">Voeg klanten toe (max 8)</h4>
          <small class="text-muted">Leeftijd via geboortedatum • DELUXE per persoon</small>
        </div>

        <div class="row row-cols-1 row-cols-md-2 g-3">
          <div class="col" v-for="(k,i) in klanten" :key="i">
            <div class="customer-card border rounded p-3 bg-light">
              <strong class="d-block mb-2">Klant @{{ i + 1 }}</strong>
              <div class="row gy-3">
                <div class="col-12">
                  <label class="form-label">Geboortedatum</label>
                  <input type="date" class="form-control"
                        v-model="k.geboortedatum"
                        :name="`klanten[${i}][geboortedatum]`">
                </div>
                <div class="col-12">
                  <label class="form-label">DELUXE menu?</label>
                  <select class="form-control"
                          v-model="k.deluxe_menu"
                          :name="`klanten[${i}][deluxe_menu]`">
                    <option value="0">Nee</option>
                    <option value="1">Ja</option>
                  </select>
                </div>
                <div class="col-12 d-flex justify-content-end">
                  <button type="button" class="btn btn-sm btn-outline-danger"
                          @click="removeKlant(i)">Verwijderen</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="d-flex align-items-center gap-2 mt-3">
          <button type="button" class="btn btn-outline-primary"
                  @click="addKlant"
                  :disabled="klanten.length >= max">+ Voeg klant toe</button>
          <small class="text-muted ms-auto">@{{ klanten.length }} / @{{ max }}</small>
        </div>
      </div>
    </div>


    {{-- Gerechtenlijst --}}
    <div class="section-card mb-4">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <h4 class="mb-0">Gerechten</h4>
        <small class="text-muted">Tik op +/− om aantal te wijzigen</small>
      </div>

      @php
        $rows = $gerechten->values()->map(function($g, $i){
            return [
                'idx'       => $i,                          // originele index voor form-naam
                'id'        => $g->id,
                'naam'      => $g->naam,
                'prijs'     => (float)$g->prijs,
                'categorie' => (string)$g->gerecht_categorie,
            ];
        })->toArray();

        $cats = $gerechten->pluck('gerecht_categorie')
                  ->filter(fn($c) => filled($c))
                  ->unique()
                  ->values()
                  ->toArray();

        $oldItems = old('items', []); // om aantallen terug te zetten
      @endphp

      {{-- Gerechtenlijst (met Vue zoek/filter) --}}
      <div class="section-card mb-4" id="order-search-root"
          data-rows='@json($rows)'
          data-cats='@json($cats)'
          data-old='@json($oldItems)'>

        <div class="d-flex flex-wrap gap-2 align-items-end mb-3">
          <div class="flex-grow-1" style="min-width:240px;">
            <label class="form-label fw-semibold">Zoeken </label>
            <input type="text" class="form-control"
                  placeholder="Zoek op #nummer of naam"
                  v-model="q">
          </div>
          <div style="min-width:220px;">
            <label class="form-label fw-semibold">Categorie </label>
            <select class="form-control" v-model="cat">
              <option value="">Alle categorieën</option>
              <option v-for="c in cats" :key="c" :value="c">@{{ c }}</option>
            </select>
          </div>
          <div class="ms-auto text-muted small" style="min-width:120px;">
            @{{ filtered.length }} resultaten
          </div>
        </div>

        <datalist id="note-suggestions">
          @foreach($opmerkingSuggesties as $opt)
            <option value="{{ $opt }}"></option>
          @endforeach
        </datalist>
        <div class="table-responsive-md">
          <table class="table table-bordered align-middle mb-0 table-sticky">
            <thead>
              <tr>
                <th style="width:70px;">#</th>
                <th>Gerecht</th>
                <th style="width:140px;">Prijs</th>
                <th style="width:180px;">Aantal</th>
                <th style="width:260px;">Opmerking</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="row in filtered" :key="row.id">
                <td>@{{ row.id }}</td>
                <td class="fw-semibold">
                  @{{ row.naam }}
                  <div class="text-muted small">@{{ row.categorie }}</div>
                </td>
                <td>€ @{{ money(row.prijs) }}</td>
                <td>
                  <div class="input-group qty-group">
                    <button type="button" class="btn btn-outline-secondary" @click="step(row.idx, -1)">−</button>
                    <input type="number" min="0" class="form-control qty-input"
                          :name="`items[${row.idx}][aantal]`"
                          v-model.number="qty[row.idx]">
                    <button type="button" class="btn btn-outline-secondary" @click="step(row.idx, 1)">+</button>
                  </div>
                  <input type="hidden" :name="`items[${row.idx}][gerecht_id]`" :value="row.id">
                </td>
                <td>
                  <input
                    type="text"
                    class="form-control input-remark"
                    :name="`items[${row.idx}][opmerking]`"
                    list="note-suggestions"
                    placeholder="Bijv. geen ui toevoegen"
                    v-model="remarks[row.idx]"
                  >
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="d-grid d-md-flex gap-2">
      <button type="submit" class="btn btn-success btn-lg w-100 w-md-auto">🧾 Bestelling opslaan</button>
      <a href="{{ route('admin.orders.index') }}" class="btn btn-outline btn-lg w-100 w-md-auto">Annuleren</a>
    </div>
  </form>
</div>

@push('scripts')
  @vite('resources/js/order-form.js')
  @vite('resources/js/order-search.js')
@endpush


{{-- JavaScript --}}
<script>
  function toggleKlantenSection() {
    const tafel = document.getElementById('tafel_id').value;
    document.getElementById('klanten-section').style.display = tafel ? 'block' : 'none';
  }

  document.addEventListener('DOMContentLoaded', function () {
    toggleKlantenSection();
    const invalid = document.querySelector('.is-invalid');
    if (invalid) invalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
  });

  function stepQty(idx, delta) {
    const input = document.getElementById('qty_' + idx);
    const current = parseInt(input.value || '0', 10);
    const next = Math.max(0, current + delta);
    input.value = next;
  }
</script>
@endsection
