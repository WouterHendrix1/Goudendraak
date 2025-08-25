@extends('layouts.app')
@section('title', 'Nieuws Aanpassen')

@section('content')
    <h1>Nieuws Aanpassen</h1>

    <form action="{{ route('admin.news.update', $newsItem->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="title">Titel</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ $newsItem->title }}" required>
        </div>
        <div class="form-group">
            <label for="content">Inhoud</label>
            <textarea name="content" id="content" class="form-control" rows="5" required>{{ $newsItem->content }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Bijwerken</button>
    </form>
@endsection
