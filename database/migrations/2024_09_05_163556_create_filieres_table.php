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
        Schema::create('filieres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cycle_id')->constrained('cycles');
            $table->string('domaine')->default(null);
            $table->foreignId('departement_id')->constrained('departements');
            $table->string('specialite');
            $table->string('parcours')->default(null);
            $table->string('option');
            $table->foreignId('ecole_id')->constrained('ecoles');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filieres');
    }
};
