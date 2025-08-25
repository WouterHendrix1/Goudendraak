@extends('layouts.app')
@section('title', 'Beheer Gerechten')

@section('content')
    <h1>Beheer Gerechten</h1>

    <a href="{{ route('admin.dishes.create') }}" class="btn btn-primary">Voeg Gerecht Toe</a>

    <table class="table">
        <thead>
            <tr>
                <th>Naam</th>
                <th>Prijs</th>
                <th>Categorie</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dishes as $dish)
                <tr>
                    <td>{{ $dish->naam }}</td>
                    <td>{{ $dish->prijs }}</td>
                    <td>{{ $dish->gerecht_categorie }}</td>
                    <td>
                        <a href="{{ route('admin.dishes.edit', $dish->id) }}" class="btn btn-warning">Bewerken</a>
                        <form action="{{ route('admin.dishes.destroy', $dish->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Verwijderen</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @if ($dishes->hasPages())
        <div class="pagination-container">
            @if ($dishes->onFirstPage())
                <div class="pagination-btn disabled">← Vorige</div>
            @else
                <a href="{{ $dishes->previousPageUrl() }}" class="pagination-btn">← Vorige</a>
            @endif

            @if ($dishes->hasMorePages())
                <a href="{{ $dishes->nextPageUrl() }}" class="pagination-btn">Volgende →</a>
            @else
                <div class="pagination-btn disabled">Volgende →</div>
            @endif
        </div>
    @endif
@endsection
