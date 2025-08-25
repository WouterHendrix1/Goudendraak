<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DailySalesReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $dateStr;
    public string $storagePath; // relative to storage/app

    public function __construct(string $dateStr, string $storagePath)
    {
        $this->dateStr = $dateStr;
        $this->storagePath = $storagePath;
    }

    public function build()
    {
        return $this->subject("Dagrapport omzet – {$this->dateStr}")
            ->markdown('emails.daily_sales')
            ->attachFromStorage($this->storagePath, "dagrapport-{$this->dateStr}.xlsx", [
                'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
    }
}
