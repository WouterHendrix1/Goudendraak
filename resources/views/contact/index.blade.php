@extends('layouts.app')

@section('content')
    <h1>Onze Locatie</h1>
    <p>Bezoek ons op onderstaand adres:</p>
    <p><strong>De Gouden Draak</strong><br>
    Onderwijsboulevard<br>
    5223 DE 's-Hertogenbosch</p>

    <iframe 
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2467.526848269369!2d5.293222976936507!3d51.68870417184353!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c6f7f07c0c0f0d%3A0x8ffb4f6b2f18b8f0!2sOnderwijsboulevard%2C%20's-Hertogenbosch!5e0!3m2!1snl!2snl!4v1690000000000!5m2!1snl!2snl"
        width="100%" 
        height="400" 
        style="border:0;" 
        allowfullscreen="" 
        loading="lazy">
    </iframe>
@endsection
