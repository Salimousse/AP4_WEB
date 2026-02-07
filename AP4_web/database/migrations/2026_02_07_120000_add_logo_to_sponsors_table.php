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
        Schema::table('SPONSORS', function (Blueprint $table) {
            if (!Schema::hasColumn('SPONSORS', 'LOGOSPONSOR')) {
                $table->string('LOGOSPONSOR')->nullable()->after('NOMSPONSORS');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('SPONSORS', function (Blueprint $table) {
            $table->dropColumn('LOGOSPONSOR');
        });
    }
};
