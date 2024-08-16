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
        Schema::create('bulletins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('classe_id')->constrained('classes');
            $table->string('annee_scolaire');
            $table->foreignId('trimestre_id')->constrained('trimestres');
            $table->float('moyenne_generale')->default(null);
            $table->string('commentaire');
            $table->foreignId('sequence_id')->constrained('sequences');
            $table->integer('heures_absences');
            $table->float('total_points');
            $table->integer('total_coef');
            $table->string('resultat');
            $table->foreignId('ecole_id')->constrained('ecoles');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulletins');
    }
};
