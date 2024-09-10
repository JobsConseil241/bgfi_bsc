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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('titre')->nullable();
            $table->string('stitre')->nullable();
            $table->string('titre_service')->nullable();
            $table->string('logo')->nullable();
            $table->string('faq_titre')->nullable();
            $table->string('faq_stitre')->nullable();
            $table->string('faq_logo')->nullable();
            $table->string('consult_titre')->nullable();
            $table->string('consult_stitre')->nullable();
            $table->string('consult_logo')->nullable();
            $table->string('avis_titre')->nullable();
            $table->string('avis_stitre')->nullable();
            $table->string('avis_logo')->nullable();
            $table->string('recla_titre')->nullable();
            $table->string('recla_stitre')->nullable();
            $table->string('recla_logo')->nullable();
            $table->integer('agence_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
