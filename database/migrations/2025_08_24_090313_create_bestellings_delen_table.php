<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('bestellings_delen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bestelling_id')->constrained('bestellingen')->cascadeOnDelete();
            $table->unsignedTinyInteger('index'); // 1..8
            $table->string('naam')->nullable();
            $table->timestamps();

            $table->unique(['bestelling_id', 'index']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('bestellings_delen');
    }
};
