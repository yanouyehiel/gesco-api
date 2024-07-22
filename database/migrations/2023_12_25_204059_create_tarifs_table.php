<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tarifs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_classe_id')->constrained('type_classes');
            $table->foreignId('ecole_id')->constrained('ecoles');
            $table->integer('inscription')->default(0);
            $table->integer('premiere_tranche')->default(0);
            $table->integer('deuxieme_tranche')->default(0);
            $table->integer('troisieme_tranche')->default(0);
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
        Schema::dropIfExists('tarifs');
    }
};
