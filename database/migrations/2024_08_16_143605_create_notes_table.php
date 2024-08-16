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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->float('note');
            $table->foreignId('matiere_id')->constrained('matieres');
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('classe_id')->constrained('classes');
            $table->foreignId('sequence_id')->constrained('sequences');
            $table->string('appreciation')->default(null);
            $table->foreignId('ecole_id')->constrained('ecoles');
            $table->string('annee_scolaire')->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
