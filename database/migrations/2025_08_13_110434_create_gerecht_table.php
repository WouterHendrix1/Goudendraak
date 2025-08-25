<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gerecht', function (Blueprint $t) {
            $t->increments('id');
            $t->string('naam', 45);
            $t->decimal('prijs', 10, 2);
            $t->mediumText('beschrijving')->nullable();
            $t->string('gerecht_categorie', 45);  // FK naar categorie.naam
            $t->foreign('gerecht_categorie')
            ->references('naam')->on('gerecht_categorie')
            ->cascadeOnUpdate()->restrictOnDelete();

            // indexen voor sneller zoeken
            $t->index('naam');
            $t->index('gerecht_categorie');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gerecht');
    }
};
