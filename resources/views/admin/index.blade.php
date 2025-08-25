@extends('layouts.app')
@section('title','Admin Dashboard')

@section('content')
    <h1>Admin Dashboard</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="dashboard">
        <div class="card">
            <h2>📦 Bestellingen</h2>
            <p>Totaal: {{ $orderCount }}</p>
            <p>Openstaand: {{ $openOrders }}</p>
            <p>Omzet: € {{ number_format($totalRevenue, 2, ',', '.') }}</p>
            <a href="{{ route('admin.orders.index') }}" class="btn">Bekijk bestellingen</a>
        </div>

        <div class="card">
            <h2>🥡 Gerechten</h2>
            <p>Totaal: {{ $dishCount }}</p>
            <a href="{{ route('admin.dishes.index') }}" class="btn">Beheer gerechten</a>
        </div>

        <div class="card">
            <h2>📰 Nieuws</h2>
            <p>Totaal berichten: {{ $newsCount }}</p>
            <a href="{{ route('admin.news.index') }}" class="btn">Beheer nieuws</a>
        </div>

        <div class="card">
            <h2>💰 Verkoop</h2>
            <a href="{{ route('admin.sales.index') }}" class="btn">Bekijk verkoopoverzicht</a>
        </div>
    </div>
@endsection
