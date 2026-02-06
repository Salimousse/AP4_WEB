<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('CLIENT', function (Blueprint $table) {
        $table->string('google_id')->nullable()->after('MAILCLIENT');
        $table->string('password')->nullable()->change(); // Rendre le mot de passe nullable
        
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('CLIENT', function (Blueprint $table) {
            $table->dropColumn('google_id');
        });
    }
};
