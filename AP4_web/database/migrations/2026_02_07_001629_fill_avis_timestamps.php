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
        // Remplir created_at et updated_at pour tous les avis existants
        \DB::update('UPDATE AVIS SET created_at = NOW(), updated_at = NOW() WHERE created_at IS NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // On ne peut pas vraiment revenir en arrière ici, car on ne sait pas les valeurs originales
    }
};
