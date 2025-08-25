<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bestellingen', function (Blueprint $table) {
            $table->foreignId('tafel_id')
                  ->nullable()
                  ->constrained('tafels')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('bestellingen', function (Blueprint $table) {
            $table->dropForeign(['tafel_id']);
            $table->dropColumn('tafel_id');
        });
    }
};
