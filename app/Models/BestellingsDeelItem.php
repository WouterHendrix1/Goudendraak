<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BestellingsDeelItem extends Model
{
    protected $table = 'bestellings_deel_items';
    protected $fillable = ['bestellings_deel_id', 'bestel_regel_id', 'aantal'];

    public function deel()
    {
        return $this->belongsTo(BestellingsDeel::class, 'bestellings_deel_id');
    }

    public function regel()
    {
        return $this->belongsTo(BestelRegel::class, 'bestel_regel_id');
    }
}
