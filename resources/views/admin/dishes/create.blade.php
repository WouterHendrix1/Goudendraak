@extends('layouts.app')

@section('title', 'Voeg Gerecht Toe')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Voeg Gerecht Toe</h1>

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

    <form action="{{ route('admin.dishes.createCategory') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="naam" class="form-label">Naam</label>
            <input type="text" name="naam" id="naam" class="form-control" required autofocus value="{{ old('naam') }}">
        </div>

        <button type="submit" class="btn btn-success">Categorie Toevoegen</button>
    </form>

    <form action="{{ route('admin.dishes.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="naam" class="form-label">Naam</label>
            <input type="text" name="naam" id="naam" class="form-control" required autofocus value="{{ old('naam') }}">
        </div>

        <div class="mb-3">
            <label for="prijs" class="form-label">Prijs (€)</label>
            <input type="number" name="prijs" id="prijs" class="form-control" step="0.01" min="0" required value="{{ old('prijs') }}">
        </div>

        <div class="mb-3">
            <label for="beschrijving" class="form-label">Beschrijving</label>
            <textarea name="beschrijving" id="beschrijving" class="form-control" rows="3">{{ old('beschrijving') }}</textarea>
        </div>

        <div class="mb-4">
            <label for="categorie" class="form-label">Categorie</label>
            <select name="gerecht_categorie" id="categorie" class="form-select" required>
                <option value="">-- Selecteer een categorie --</option>
                @foreach($categories as $categorie)
                    <option value="{{ $categorie->naam }}" {{ old('gerecht_categorie') == $categorie->naam ? 'selected' : '' }}>
                        {{ $categorie->naam }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Toevoegen</button>
        <a href="{{ route('admin.dishes.index') }}" class="btn btn-secondary">Annuleren</a>
    </form>
</div>
@endsection
