@extends('layouts.app')
@section('title', 'Verkoopoverzicht')

@section('content')
<h1>Verkoopoverzicht</h1>
<form method="get" action="{{ route('admin.sales.index') }}" style="margin-bottom:20px;">
    <label for="van">Van: </label>
    <input type="date" name="van" value="{{ $van }}">
    <label for="tot">Tot: </label>
    <input type="date" name="tot" value="{{ $tot }}">
    <button type="submit" class="btn btn-primary">Toon overzicht</button>
</form>

<table class="table">
    <thead>
        <tr>
            <th>Gerecht</th>
            <th>Aantal verkocht</th>
            <th>Totaal omzet</th>
        </tr>
    </thead>
    <tbody>
        @foreach($statistiek as $item)
            <tr>
                <td>{{ $item['gerecht']->naam }}</td>
                <td>{{ $item['aantal'] }}</td>
                <td>€{{ number_format($item['omzet'], 2, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
