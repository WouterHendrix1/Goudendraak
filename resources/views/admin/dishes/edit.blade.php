@extends('layouts.app')

@section('title', 'Bewerk Gerecht')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Bewerk Gerecht</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Er is een fout opgetreden:</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.dishes.update', $dish->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="naam" class="form-label">Naam</label>
            <input type="text" name="naam" id="naam" class="form-control" value="{{ old('naam', $dish->naam) }}" required>
        </div>

        <div class="mb-3">
            <label for="prijs" class="form-label">Prijs (€)</label>
            <input type="number" name="prijs" id="prijs" class="form-control" step="0.01" min="0" value="{{ old('prijs', $dish->prijs) }}" required>
        </div>

        <div class="mb-3">
            <label for="beschrijving" class="form-label">Beschrijving</label>
            <textarea name="beschrijving" id="beschrijving" class="form-control" rows="3">{{ old('beschrijving', $dish->beschrijving) }}</textarea>
        </div>

        <div class="mb-4">
            <label for="categorie" class="form-label">Categorie</label>
            <select name="gerecht_categorie" id="categorie" class="form-select" required>
                <option value="">-- Selecteer een categorie --</option>
                @foreach($categories as $categorie)
                    <option value="{{ $categorie->naam }}" {{ old('gerecht_categorie', $dish->gerecht_categorie) == $categorie->naam ? 'selected' : '' }}>
                        {{ $categorie->naam }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Bijwerken</button>
        <a href="{{ route('admin.dishes.index') }}" class="btn btn-secondary">Annuleren</a>
    </form>
</div>
@endsection
