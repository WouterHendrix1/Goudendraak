<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BestellingsDeel extends Model
{
    protected $table = 'bestellings_delen';
    protected $fillable = ['bestelling_id', 'index', 'naam', 'klant_id'];

    public function bestelling()
    {
        return $this->belongsTo(Bestelling::class);
    }

    public function klant()
    {
        return $this->belongsTo(Klant::class);
    }

    public function items()
    {
        return $this->hasMany(BestellingsDeelItem::class, 'bestellings_deel_id');
    }
}
