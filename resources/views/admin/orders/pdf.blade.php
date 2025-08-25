<!doctype html>
<html lang="nl">
<head>
<meta charset="utf-8">
<title>Bon #{{ $bestelling->id }}</title>
<style>
  /* Exacte papiersize + marges */
  @page { size: 8.5cm 10cm; margin: 0.4cm 0.35cm 0.5cm; }
  body { font-family: DejaVu Sans, sans-serif; font-size: 9.5pt; color:#111; }

  /* Header */
  .header { text-align:center; margin-bottom: .15cm; }
  .logo   { width: 2.6cm; height:auto; margin: 0 auto .05cm; display:block; }
  .title  { font-weight:700; font-size: 10.5pt; margin: 0 0 .05cm; }
  .meta   { font-size: 8pt; color:#666; margin:0; }

  /* Lijnen */
  .line { border-top: .2pt solid #ddd; margin: .15cm 0; }

  /* Item-blok (twee regels per item, past beter op smalle bon) */
  .item { page-break-inside: avoid; margin-bottom: .12cm; }
  .row  { display: table; width:100%; }
  .cell { display: table-cell; vertical-align: top; }
  .left { width: 70%; }
  .right{ width: 30%; text-align: right; }

  .thumb { width: 1.6cm; height: 1.6cm; object-fit: cover; border-radius: 2pt; border: .2pt solid #ddd; }
  .name  { font-weight: 600; line-height: 1.25; }
  .small { font-size: 8pt; color:#555; }

  /* Totaalblok */
  .totals { border-top: .6pt solid #000; padding-top: .1cm; font-weight: 700; }
  .totals .label { float:left; }
  .totals .value { float:right; }
  .clearfix::after { content:""; display:block; clear:both; }

</style>
</head>
<body>

  <div class="header">
    @if($logo)
      <img class="logo" src="{{ $logo }}" alt="Logo">
    @endif
    <div class="title">De Gouden Draak</div>
    <p class="meta">
      Bon #{{ $bestelling->id }}
      @if($bestelling->tafel_id) • Tafel {{ $bestelling->tafel_id }} @else • Afhaal @endif
      • {{ $bestelling->created_at?->format('d-m-Y H:i') }}
    </p>
  </div>

  <div class="line"></div>

  {{-- Items --}}
  @foreach($items as $it)
    <div class="item">
      <div class="row">
        <div class="cell left">
          <table style="width:100%; border-collapse:collapse;">
            <tr>
              <td style="vertical-align:top;">
                <div class="name">{{ $it['naam'] }}</div>
                <div class="small">
                  Prijs/stuk: € {{ number_format($it['prijs'], 2, ',', '.') }}<br>
                  Aantal: {{ $it['aantal'] }}
                </div>
              </td>
            </tr>
          </table>
        </div>
        <div class="cell right" style="font-weight:700;">
          € {{ number_format($it['totaal'], 2, ',', '.') }}
        </div>
      </div>
    </div>
  @endforeach

  <div class="line"></div>

  {{-- Totaal --}}
  <div class="totals clearfix">
    <div class="label">Totaal</div>
    <div class="value">€ {{ number_format($totaal, 2, ',', '.') }}</div>
  </div>

</body>
</html>
