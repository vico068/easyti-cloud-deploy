<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// EASYTI: Migration para adicionar campos de multi-tenancy simplificado
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            // EASYTI: Campo para identificar o team master (Easy TI Solutions)
            $table->boolean('is_master')->default(false)->after('personal_team');
            
            // EASYTI: Plano do cliente (starter, professional, enterprise)
            $table->string('plan')->default('starter')->after('is_master');
            
            // EASYTI: Limites customizados por plano (JSON)
            $table->json('plan_limits')->nullable()->after('plan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn(['is_master', 'plan', 'plan_limits']);
        });
    }
};

