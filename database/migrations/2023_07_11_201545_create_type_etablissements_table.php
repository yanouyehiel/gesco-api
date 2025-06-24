<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_etablissements', function (Blueprint $table) {
            $table->id();
            $table->string('intitule');
        });

        // Insertion des types d'établissements après la création de la table
        DB::table('type_etablissements')->insert([
            ['intitule' => 'École Primaire'],
            ['intitule' => 'École Secondaire'],
            ['intitule' => 'Université'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('type_etablissements');
    }
};
