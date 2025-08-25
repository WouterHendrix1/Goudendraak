@extends('layouts.app')
@section('title','Bestelling bewerken')

@section('content')
<h1>Bestelling bewerken #{{ $bestelling->id }} - {{ $bestelling->tafel_id ? 'Tafel ' . $bestelling->tafel_id : 'Afhaal' }}</h1>
@if($klanten)
  <p>Aantal gasten: {{ $klanten->count() }}</p>
@endif


@if($errors->any())
  <div class="alert">
    <ul>
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Gerecht</th>
            <th>Aantal</th>
            <th>Prijs/stuk</th>
            <th>Subtotaal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($bestelling->regels as $regel)
            <tr>
                <td>{{ $regel->gerecht_id }}</td>
                <td>{{ $regel->gerecht->naam }}</td>
                <td>{{ $regel->aantal }}</td>
                <td>€ {{ number_format($regel->prijs_per_stuk, 2, ',', '.') }}</td>
                <td>€ {{ number_format($regel->aantal * $regel->prijs_per_stuk, 2, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<p><strong>Totaal: </strong>€ {{ number_format($bestelling->totaal, 2, ',', '.') }}</p>
@if($klanten)
  
  <h4>Gasten:</h4>
  <ul>
    @foreach($klanten as $klant)
      <li>Klant {{ $loop->index + 1 }}: {{ $klant->geboortedatum }} - {{ $klant->deluxe_menu ? 'DELUXE' : 'Standaard' }} - Prijs per gast: € {{ number_format($klant->prijs_per_gast, 2, ',', '.') }}</li>
    @endforeach
  </ul>
  <p><strong>Prijs per gast:</strong> €  {{ number_format($bestelling->totaal / $klanten->count(), 2, ',', '.') }}</p>
  <p><a href="{{ route('admin.orders.delen', $bestelling->id) }}" class="btn btn-secondary">Rekening opdelen per klant</a></p>
@endif


<form action="{{ route('admin.orders.update', $bestelling->id) }}" method="POST">
  @csrf
  @method('PUT')

  <div class="form-group">
    <label for="status">Status</label>
    <select name="status" id="status" class="form-control">
      <option value="open" {{ $bestelling->status === 'niet_betaald' ? 'selected' : '' }}>
        Niet betaald
      </option>
      <option value="betaald" {{ $bestelling->status === 'betaald' ? 'selected' : '' }}>
        Betaald
      </option>
    </select>
  </div>

  <div class="form-group" style="margin-top:15px;">
    <button type="submit" class="btn btn-primary">Opslaan</button>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Annuleren</a>
  </div>
</form>
<a href="{{ route('admin.orders.pdf', $bestelling->id) }}" class="btn btn-info" target="_blank" style="margin-top:15px;">Download PDF</a>

@endsection
