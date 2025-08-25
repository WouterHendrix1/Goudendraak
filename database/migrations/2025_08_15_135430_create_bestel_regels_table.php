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
        Schema::create('bestel_regels', function (Blueprint $t) {
            $t->id();
            $t->foreignId('bestelling_id')->constrained('bestellingen')->cascadeOnDelete();
            $t->unsignedInteger('gerecht_id');        // FK naar jouw bestaande 'gerecht' tabel
            $t->unsignedInteger('aantal')->default(1);
            $t->decimal('prijs_per_stuk', 10, 2);     // prijs vastleggen op bestelmoment
            $t->timestamps();

            $t->index(['bestelling_id', 'gerecht_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bestel_regels');
    }
};
