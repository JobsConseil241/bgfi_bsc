<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bgfi_comptes_tampon', function (Blueprint $table) {
            $table->id();
            $table->string('numero_compte', 10);
            $table->string('nom_complet', 255);
            $table->string('telephone', 20);
            $table->decimal('solde', 15, 2)->default(0);
            $table->string('devise', 3)->default('XAF');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bgfi_comptes_tampon');
    }
};
