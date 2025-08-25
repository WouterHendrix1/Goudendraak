<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class GerechtCategorie extends Model {
    public $timestamps = false;
    protected $table = 'gerecht_categorie';
    protected $primaryKey = 'naam';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['naam'];

    public function gerechten() {
        return $this->hasMany(Gerecht::class, 'gerecht_categorie', 'naam');
    }
}
