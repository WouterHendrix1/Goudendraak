<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bestellings_delen', function (Blueprint $table) {
            $table->foreignId('klant_id')
                  ->nullable()
                  ->after('bestelling_id')
                  ->constrained('klanten')
                  ->cascadeOnDelete();

            // 1 deel per klant per bestelling (uniek)
            $table->unique(['bestelling_id', 'klant_id']);
        });
    }

    public function down(): void
    {
        Schema::table('bestellings_delen', function (Blueprint $table) {
            $table->dropUnique(['bestelling_id', 'klant_id']);
            $table->dropConstrainedForeignId('klant_id');
        });
    }
};