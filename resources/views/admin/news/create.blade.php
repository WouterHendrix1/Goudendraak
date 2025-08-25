@extends('layouts.app')
@section('title', 'Nieuws Toevoegen')

@section('content')
    <h1>Nieuws Toevoegen</h1>

    <form action="{{ route('admin.news.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="title">Titel</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="content">Inhoud</label>
            <textarea name="content" id="content" class="form-control" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Toevoegen</button>
    </form>
@endsection
