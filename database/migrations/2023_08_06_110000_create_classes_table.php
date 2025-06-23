<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->foreignId('type_classe_id')->constrained('type_classes')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->default(null);
            $table->integer('effectif');
            $table->foreignId('ecole_id')->constrained('ecoles');
            $table->foreignId('cycle_id')->constrained('cycles');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classes');
    }
}
