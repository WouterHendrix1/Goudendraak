<?php

namespace App\Console\Commands;

use App\Mail\DailySalesReportMail;
use App\Models\Bestelling;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SendDailySalesReport extends Command
{
    protected $signature = 'report:daily-sales {--date=} {--to=}';
    protected $description = 'Genereer Excel dagrapport omzet en mail het naar de admin(s).';

    public function handle(): int
    {
        $date = $this->option('date')
            ? Carbon::parse($this->option('date'))->startOfDay()
            : now()->subDay()->startOfDay(); // gister standaard
        $from = $date->copy();
        $to   = $date->copy()->endOfDay();

        $dateStr = $date->format('Y-m-d');

        // Data ophalen
        $bestellingen = Bestelling::with(['regels.gerecht'])
            ->whereBetween('created_at', [$from, $to])
            ->orderBy('id')
            ->get();

        // Spreadsheet opbouwen
        $sheet = new Spreadsheet();
        $ws = $sheet->getActiveSheet();
        $ws->setTitle('Verkoop');

        $row = 1;
        $ws->fromArray([
            ['Dagrapport omzet', $dateStr],
            ['Gegenereerd op', now()->format('Y-m-d H:i')],
        ], null, "A{$row}");
        $row += 3;

        $ws->fromArray(['Tijd','Bestelling #','Regel #','Gerecht #','Naam','Aantal','Prijs/stuk','Regel totaal'], null, "A{$row}");
        $ws->getStyle("A{$row}:H{$row}")->getFont()->setBold(true);
        $row++;

        $dagTotaal = 0;

        foreach ($bestellingen as $b) {
            foreach ($b->regels as $r) {
                $regelTotaal = (float)$r->aantal * (float)$r->prijs_per_stuk;
                $dagTotaal += $regelTotaal;

                $ws->fromArray([
                    $b->created_at?->format('H:i'),
                    $b->id,
                    $r->id,
                    $r->gerecht_id,
                    $r->gerecht?->naam,
                    (int)$r->aantal,
                    (float)$r->prijs_per_stuk,
                    $regelTotaal,
                ], null, "A{$row}");
                $row++;
            }
        }

        // Totaal
        $ws->setCellValue("G{$row}", 'Dag totaal');
        $ws->setCellValue("H{$row}", $dagTotaal);
        $ws->getStyle("G{$row}:H{$row}")->getFont()->setBold(true);

        // Mooie kolombreedtes
        foreach (range('A','H') as $col) {
            $ws->getColumnDimension($col)->setAutoSize(true);
        }

        // Opslaan in storage/app/reports/sales/2024-09-10.xlsx
        $dir = "reports/sales";
        Storage::makeDirectory($dir);
        $relPath = "{$dir}/{$dateStr}.xlsx";
        $absPath = Storage::path($relPath);

        IOFactory::createWriter($sheet, 'Xlsx')->save($absPath);

        // Mailen
        $to = $this->option('to') ?: config('reports.daily_sales_recipients');
        $emails = collect(explode(',', (string)$to))
            ->map(fn($e)=>trim($e))
            ->filter();

        if ($emails->isEmpty()) {
            $this->warn('Geen ontvangers ingesteld (reports.daily_sales_recipients).');
        } else {
            foreach ($emails as $email) {
                Mail::to($email)->send(new DailySalesReportMail($dateStr, $relPath));
            }
            $this->info("Rapport gemaild naar: ".$emails->join(', '));
        }

        $this->info("Bestand opgeslagen: storage/app/{$relPath}");
        return Command::SUCCESS;
    }
}