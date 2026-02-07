<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * ⚠️ MIGRATION OPTIONNELLE
     * 
     * Cette migration n'est PAS nécessaire pour que le système fonctionne.
     * Elle ne fait qu'ajouter user_id pour tracker les utilisateurs.
     * 
     * Si vous ne voulez pas tracer les users → vous pouvez IGNORER cette migration.
     * 
     * Le système de nettoyage fonctionne sans elle, basé juste sur :
     * - admin_active = true/false
     * - created_at 
     */
    public function up(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            if (!Schema::hasColumn('conversations', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            if (Schema::hasColumn('conversations', 'user_id')) {
                $table->dropForeignKeyIfExists(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};
