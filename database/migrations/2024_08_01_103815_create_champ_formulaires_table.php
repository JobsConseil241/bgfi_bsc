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
        Schema::create('champ_formulaires', function (Blueprint $table) {
            $table->id();
            $table->string('intitulé');
            $table->string('type');
            $table->boolean('is_required')->default(false);
            $table->integer('position')->default(0)->unsigned();
            $table->integer('id_formulaire')->unsigned();
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('champ_formulaires');
    }
};
