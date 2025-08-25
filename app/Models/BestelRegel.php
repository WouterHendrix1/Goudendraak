<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BestelRegel extends Model
{
    protected $fillable = ['bestelling_id','gerecht_id','aantal','prijs_per_stuk', 'opmerking'];

    public function bestelling()
    {
        return $this->belongsTo(Bestelling::class);
    }
    public function gerecht()
    {
        return $this->belongsTo(Gerecht::class);
    }

    public function deelItems()
    {
        return $this->hasMany(BestellingsDeelItem::class, 'bestel_regel_id');
    }

}