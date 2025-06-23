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
        Schema::create('recap_trimestres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students');
            $table->float('moyenne');
            $table->foreignId('trimestre_id')->constrained('trimestres');
            $table->float('rang');
            $table->string('annee_scolaire');
            $table->foreignId('ecole_id')->constrained('ecoles');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recap_trimestres');
    }
};
