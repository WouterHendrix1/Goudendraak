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
        Schema::table('klanten', function (Blueprint $table) {
            $table->unsignedBigInteger('bestelling_id')->nullable()->after('deluxe_menu');
            $table->foreign('bestelling_id')->references('id')->on('bestellingen')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('klanten', function (Blueprint $table) {
            $table->dropForeign(['bestelling_id']);
            $table->dropColumn('bestelling_id');
        });
    }
};
