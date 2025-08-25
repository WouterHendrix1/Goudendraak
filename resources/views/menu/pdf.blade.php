<!doctype html>
<html lang="nl">
<head>
<meta charset="utf-8">
<title>Menu</title>
<style>
  @page { margin: 1.5cm 1.2cm; }
  body { font-family: DejaVu Sans, sans-serif; font-size: 11pt; color:#111; }
  h1,h2 { margin: 0 0 .45rem; }
  .header { text-align:center; margin-bottom: .9cm; }
  .logo { height: 52px; margin-bottom: .35rem; }
  .muted { color:#666; font-size: 9pt; }
  .cat { margin-bottom: 1cm; page-break-inside: avoid; }
  table { width:100%; border-collapse: collapse; }
  th, td { padding: .38rem .45rem; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
  th { text-align:left; font-weight:700; background:#f8f9fa; }
  .col-naam { width: 48%; }
  .col-omsch{ width: 32%; }
  .col-prijs{ width: 20%; text-align: right; white-space: nowrap; }
</style>
</head>
<body>
  <div class="header">
    @if($logo)
      <img class="logo" src="{{ $logo }}" alt="Logo">
    @endif
    <h1>Menu</h1>
    <div class="muted">Laatst bijgewerkt: {{ now()->format('d-m-Y') }}</div>
  </div>

  @foreach($groepen as $categorie => $items)
    <section class="cat">
      <h2>{{ $categorie ?: 'Overig' }}</h2>
      <table>
        <thead>
          <tr>
            <th class="col-naam">Gerecht</th>
            <th class="col-omsch">Omschrijving</th>
            <th class="col-prijs">Prijs</th>
          </tr>
        </thead>
        <tbody>
          @foreach($items as $g)
            <tr>
              <td class="col-naam">{{ $g->naam }}</td>
              <td class="col-omsch">{{ $g->beschrijving }}</td>
              <td class="col-prijs">€ {{ number_format((float)$g->prijs, 2, ',', '.') }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </section>
  @endforeach
</body>
</html>
