<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('bestel_regels', function (Blueprint $t) {
            $t->string('opmerking', 255)->nullable()->after('prijs_per_stuk');
        });
    }
    
    public function down(): void {
        Schema::table('bestel_regels', function (Blueprint $t) {
            $t->dropColumn('opmerking');
        });
    }
};
