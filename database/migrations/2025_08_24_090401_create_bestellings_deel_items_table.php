<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('bestellings_deel_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bestellings_deel_id')->constrained('bestellings_delen')->cascadeOnDelete();
            $table->foreignId('bestel_regel_id')->constrained('bestel_regels')->cascadeOnDelete();
            $table->unsignedInteger('aantal');
            $table->timestamps();

            $table->index(['bestellings_deel_id', 'bestel_regel_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('bestellings_deel_items');
    }
};
