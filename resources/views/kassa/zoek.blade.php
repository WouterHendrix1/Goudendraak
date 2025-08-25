<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Kassa – Zoeken</title>
  <style>
    body{font-family:system-ui,Arial,sans-serif;max-width:920px;margin:24px auto}
    form{display:flex;gap:8px;align-items:center;margin-bottom:16px}
    input,select,button{padding:8px}
    table{width:100%;border-collapse:collapse}
    th,td{padding:8px;border-bottom:1px solid #eee;text-align:left}
  </style>
</head>
<body>
  <h1>Kassa – Zoeken</h1>

  <form method="get" action="{{ route('kassa.zoek') }}">
    <input type="text" name="q" value="{{ $q }}" placeholder="Zoek op naam of nummer…">
    <select name="categorie">
      <option value="">Alle categorieën</option>
      @foreach($cats as $c)
        <option value="{{ $c->naam }}" @selected($cat===$c->naam)>{{ $c->naam }}</option>
      @endforeach
    </select>
    <button type="submit">Zoeken</button>
  </form>

  <table>
    <thead>
      <tr><th>#</th><th>Naam</th><th>Categorie</th><th>Prijs</th></tr>
    </thead>
    <tbody>
      @forelse($resultaten as $g)
        <tr>
          <td>{{ $g->id }}</td>
          <td>{{ $g->naam }}</td>
          <td>{{ $g->categorie->naam }}</td>
          <td>€ {{ number_format($g->prijs,2,',','.') }}</td>
        </tr>
      @empty
        <tr><td colspan="4">Geen resultaten…</td></tr>
      @endforelse
    </tbody>
  </table>
</body>
</html>
