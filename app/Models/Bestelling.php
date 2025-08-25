<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bestelling extends Model
{
    protected $table = 'bestellingen';
    protected $fillable = ['datum','totaal','status', 'tafel_id'];

    public function tafel()
    {
        return $this->belongsTo(Tafel::class);
    }

    public function regels()
    {
        return $this->hasMany(BestelRegel::class);
    }

    public function klanten()
    {
        return $this->hasMany(Klant::class);
    }

    public function delen()
    {
        return $this->hasMany(BestellingsDeel::class);
    }

}