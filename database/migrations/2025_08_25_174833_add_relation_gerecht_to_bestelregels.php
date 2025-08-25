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
        Schema::table('bestel_regels', function (Blueprint $table) {
            // ensure type matches gerecht.id (int unsigned in your schema)
            $table->unsignedInteger('gerecht_id')->change();

            // add index + FK (table name is 'gerecht' in your DB)
            $table->foreign('gerecht_id', 'bestel_regels_gerecht_fk')
                  ->references('id')->on('gerecht')
                  ->onUpdate('cascade')
                  ->onDelete('restrict'); // or ->onDelete('cascade') if you prefer
        });
    }

    public function down(): void
    {
        Schema::table('bestel_regels', function (Blueprint $table) {
            $table->dropForeign('bestel_regels_gerecht_fk');
        });
    }
};
