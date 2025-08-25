@extends('layouts.app')
@section('title','Bestellingen')

@section('content')
<h1>Bestellingen overzicht</h1>

@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

    <a href="{{ route('admin.orders.create') }}" class="btn btn-primary" style="margin-bottom:15px;">
        ➕ Nieuwe bestelling
    </a>

<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Datum</th>
            <th>Aantal items</th>
            <th>Totaal</th>
            <th>Status</th>
            <th>Tafel</th>
            <th>Acties</th>
        </tr>
    </thead>
    <tbody>
        @foreach($bestellingen as $b)
            <tr>
                <td>{{ $b->id }}</td>
                <td>{{ $b->created_at->format('d-m-Y H:i') }}</td>
                <td>{{ $b->regels->count() }}</td>


                <td>€ {{ number_format($b->totaal, 2, ',', '.') }}</td>
                <td>{{ $b->status }}</td>
                <td>{{ $b->tafel ? $b->tafel->id : 'Afhaal' }}</td>
                <td>
                    <a href="{{ route('admin.orders.show',$b->id) }}">Bekijken</a> | 
                    <a href="{{ route('admin.orders.edit',$b->id) }}">Bewerken</a> | 
                    <form action="{{ route('admin.orders.destroy',$b->id) }}" method="POST" style="display:inline">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('Weet je zeker dat je deze bestelling wil verwijderen?')">Verwijderen</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $bestellingen->links() }}
@endsection
