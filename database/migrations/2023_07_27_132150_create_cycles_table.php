<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cycles', function (Blueprint $table) {
            $table->id();
            $table->string('intitule');
            $table->foreignId('ecole_id')->nullable()->constrained('ecoles')->default(null);
            $table->timestamps();
        });

        // Insertion des cycles après la création de la table
        DB::table('cycles')->insert([
            ['intitule' => 'Premier Cycle'],
            ['intitule' => 'Second Cycle']
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cycles');
    }
};
