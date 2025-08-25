@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/menu.css') }}">
@endpush

@section('content')
  <div class="menu-container">

    <div class="menu-header">
      <h1 class="mb-0">Menukaart</h1>
      <a href="{{ route('menu.pdf') }}" class="btn btn-primary">📄 Download PDF</a>
    </div>

    <div id="menu-root"
         data-rows='@json($rows)'
         data-cats='@json($cats)'>
      <div class="menu-toolbar toolbar-card">
  <div class="field grow">
    <label class="form-label fw-semibold">Zoeken</label>
    <div class="control has-icon">
      <input
        type="text"
        class="form-control form-control-lg"
        placeholder="Zoek op #nummer of naam"
        v-model="q"
      >
      <span class="icon icon-search" aria-hidden="true"></span>
      <button
        type="button"
        class="btn-clear"
        v-if="q"
        @click="q = ''"
        aria-label="Zoekveld leegmaken"
      >×</button>
    </div>
  </div>

  <div class="field">
    <label class="form-label fw-semibold">Categorie</label>
    <select class="form-select form-select-lg" v-model="cat">
      <option value="">Alle categorieën</option>
      <option v-for="c in cats" :key="c" :value="c">@{{ c }}</option>
    </select>
  </div>

  <div class="field">
    <label class="form-label fw-semibold">Sorteren</label>
    <select class="form-select form-select-lg" v-model="sortMode">
      <option value="default">Standaard (op nummer)</option>
      <option value="fav-first-number">Favorieten eerst (op nummer)</option>
      <option value="fav-alpha-top">Favorieten alfabetisch bovenaan</option>
    </select>
  </div>

  <div class="toolbar-meta">
    <span class="badge-count">@{{ filtered.length }}</span> resultaten
  </div>
</div>

      <div class="menu-grid">
        <div class="menu-item" v-for="g in filtered" :key="g.id">
          <button type="button"
                  class="fav-btn"
                  :class="isFav(g.id) ? 'is-fav' : ''"
                  @click="toggleFav(g.id)"
                  :aria-pressed="isFav(g.id)">
            <span v-if="isFav(g.id)">★</span><span v-else>☆</span>
          </button>
          <div class="code">#@{{ g.id }}</div>
          <div class="name">@{{ g.naam }}</div>
          <div class="cat">@{{ g.categorie }}</div>
          <div class="price">€ @{{ money(g.prijs) }}</div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
@vite('resources/js/menu.js')
@endpush
