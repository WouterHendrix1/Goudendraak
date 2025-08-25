<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tafel extends Model
{
    protected $guarded = [];

    public function klanten()
    {
        return $this->hasMany(Klant::class);
    }
    public function bestellingen()
    {
        return $this->hasMany(Bestelling::class);
    }
}
