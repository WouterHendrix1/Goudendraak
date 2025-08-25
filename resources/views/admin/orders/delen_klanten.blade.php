@extends('layouts.app')
@section('title','Rekening opdelen per klant')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/split-bill.css') }}">
@endpush

@section('content')
{{-- TIP: container-fluid geeft meer breedte op tablet --}}
<div class="split-bill container-fluid px-3 px-md-4">

  <div class="page-header">
    <h1 class="page-title h2">
      Rekening opdelen per klant <small>— Bestelling #{{ $bestelling->id }}</small>
    </h1>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.orders.storeDelen', $bestelling->id) }}">
    @csrf

    <section class="card-xl">
      <div class="card-body">
        <h5 class="card-title">Verdeel per gerecht naar klanten</h5>

        <div class="table-wrap">
          <table class="table-split">
            <thead>
              <tr>
                <th class="col-id">#</th>
                <th class="col-dish">Gerecht</th>
                <th class="col-price">Prijs per stuk</th>
                <th class="col-qty">Aantal</th>
                @foreach($bestelling->klanten as $k)
                  <th class="col-cust text-center">Klant {{ $loop->index + 1 }}</th>
                @endforeach
              </tr>
            </thead>

            <tbody>
              @foreach($bestelling->regels as $rIdx => $regel)
                @php
                  $initMap = $huidigeVerdeling[$regel->id] ?? [];
                  $som = array_sum($initMap);
                  $invalid = $som > $regel->aantal;
                @endphp
                <tr class="{{ $invalid ? 'row-invalid' : '' }}">
                  <td class="text-center">
                    {{ $regel->gerecht_id }}
                    {{-- hidden meegeven binnen een <td> (correct HTML) --}}
                    <input type="hidden" name="items[{{ $rIdx }}][regel_id]" value="{{ $regel->id }}">
                  </td>
                  <td>{{ $regel->gerecht->naam }}</td>
                  <td>€ {{ number_format($regel->gerecht->prijs,2,',','.') }}</td>  
                  <td class="text-center">{{ $regel->aantal }}</td>

                  @foreach($bestelling->klanten as $klant)
                    @php $init = $initMap[$klant->id] ?? 0; @endphp
                    <td class="text-center">
                      <input
                        type="number" min="0"
                        class="form-control input-qty"
                        name="items[{{ $rIdx }}][klanten][{{ $klant->id }}]"
                        value="{{ old('items.'.$rIdx.'.klanten.'.$klant->id, $init) }}"
                      >
                    </td>
                  @endforeach
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="actions">
          <button class="btn btn-primary">Verdeling opslaan</button>
          <a href="{{ route('admin.orders.edit', $bestelling->id) }}" class="btn btn-light border">Terug</a>
        </div>
      </div>
    </section>
  </form>
</div>
@endsection
