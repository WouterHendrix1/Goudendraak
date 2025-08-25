@extends('layouts.app')
@section('title','Bestelling #'.$bestelling->id)

@section('content')
  @if(session('ok')) <div class="alert">{{ session('ok') }}</div> @endif

  <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">⬅ Terug naar bestellingen</a>

  <h1>Bestelling #{{ $bestelling->id }}</h1>
  <p>Status: <strong>{{ $bestelling->status }}</strong></p>
  <p>Datum: {{ $bestelling->created_at?->format('d-m-Y H:i') ?? $bestelling->datum }}</p>

  <table class="table">
    <thead>
      <tr><th>#</th><th>Gerecht</th><th>Aantal</th><th>Prijs/stuk</th><th>Subtotaal</th></tr>
    </thead>
    <tbody>
      @foreach($bestelling->regels as $r)
        <tr>
          <td>{{ $r->gerecht_id }}</td>
          <td>{{ \App\Models\Gerecht::find($r->gerecht_id)->naam ?? 'Onbekend' }}</td>
          <td>{{ $r->aantal }}</td>
          <td>€ {{ number_format($r->prijs_per_stuk,2,',','.') }}</td>
          <td>€ {{ number_format($r->aantal * $r->prijs_per_stuk,2,',','.') }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <p><strong>Totaal: € {{ number_format($bestelling->totaal,2,',','.') }}</strong></p>
@endsection
