@extends('layouts.app')
@section('title', 'Beheer Nieuws')

@section('content')
    <h1>Beheer Nieuws</h1>

    <a href="{{ route('admin.news.create') }}" class="btn btn-primary">Voeg Nieuws Toe</a>

    @if($newsItems->isEmpty())
        <p>Geen nieuwsitems gevonden.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Titel</th>
                <th>Inhoud</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tbody>
            @foreach($newsItems as $item)
                <tr>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->content }}</td>
                    <td>
                        <a href="{{ route('admin.news.edit', $item->id) }}" class="btn btn-warning">Bewerken</a>
                        <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Verwijderen</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

        @if ($newsItems->hasPages())
            <div class="pagination-container">
                @if ($newsItems->onFirstPage())
                    <div class="pagination-btn disabled">← Vorige</div>
                @else
                    <a href="{{ $newsItems->previousPageUrl() }}" class="pagination-btn">← Vorige</a>
                @endif

                @if ($newsItems->hasMorePages())
                    <a href="{{ $newsItems->nextPageUrl() }}" class="pagination-btn">Volgende →</a>
                @else
                    <div class="pagination-btn disabled">Volgende →</div>
                @endif
            </div>
        @endif
    @endif

@endsection
