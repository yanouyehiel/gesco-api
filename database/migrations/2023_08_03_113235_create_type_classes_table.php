<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTypeClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_classes', function (Blueprint $table) {
            $table->id();
            $table->string('classe');
            $table->foreignId('ecole_id')->constrained('ecoles')->nullable();
            $table->timestamps();
        });

        // Insertion des cycles après la création de la table
        /*DB::table('type_classes')->insert([
            ['classe' => 'Petite Section'],
            ['classe' => 'Moyenne Section'],
            ['classe' => 'Grande Section'],
            ['classe' => 'SIL'],
            ['classe' => 'CP'],
            ['classe' => 'CE1'],
            ['classe' => 'CE2'],
            ['classe' => 'CM1'],
            ['classe' => 'CM2'],
            ['classe' => 'Moyenne Section'],
            ['classe' => 'Moyenne Section'],
            ['classe' => 'Moyenne Section'],
            ['classe' => 'Moyenne Section'],
        ]);*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('type_classes');
    }
}
