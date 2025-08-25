@extends('layouts.app')

@section('content')
    <h1>Nieuws</h1>
    @forelse($newsItems as $item)
        <div class="news-item">
            <h2>{{ $item->title }}</h2>
            <small>Geplaatst op: {{ \Carbon\Carbon::parse($item->published_at)->format('d-m-Y') }}</small>
            <p>{{ $item->content }}</p>
        </div>
    @empty
        <p>Er is momenteel geen nieuws beschikbaar.</p>
    @endforelse
@endsection
