@component('mail::message')
# Dagrapport omzet – {{ $dateStr }}

In de bijlage vind je de Excel-export met alle bestellingen en totalen voor {{ $dateStr }}.

Groeten,<br>
{{ config('app.name') }}
@endcomponent
