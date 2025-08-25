<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Klant extends Model
{
    protected $table = 'klanten';
    protected $fillable = [
        'tafel_id',
        'geboortedatum',
        'deluxe_menu',
        'bestelling_id',
    ];
        
    public function tafel()
    {
        return $this->belongsTo(Tafel::class);
    }

    public function delen()
    {
        return $this->hasMany(BestellingsDeel::class);
    }

    public function bestellingen()
    {
        return $this->belongsTo(Bestelling::class);
    }
}
