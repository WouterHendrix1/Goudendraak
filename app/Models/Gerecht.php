<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Gerecht extends Model {
    public $timestamps = false;
    protected $table = 'gerecht';
    protected $fillable = ['naam','prijs','beschrijving','gerecht_categorie'];

    public function categorie() {
        return $this->belongsTo(GerechtCategorie::class, 'gerecht_categorie', 'naam');
    }
}
