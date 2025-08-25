<?php

// database/seeders/CategorieSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Soepen',
            'Voorgerechten',
            'Bami/Nasi gerechten',
            'Combinatiegerechten',
            'Mihoen gerechten',
            'Chinese Bami gerechten',
            'Indische gerechten',
            'Eiergerechten',
            'Groenten gerechten',
            'Vlees gerechten',
            'Kipgerechten',
            'Garnalen gerechten',
        ];

        foreach ($categories as $cat) {
            DB::table('gerecht_categorie')->insert([
                'naam' => $cat
            ]);
        }
    }
}
